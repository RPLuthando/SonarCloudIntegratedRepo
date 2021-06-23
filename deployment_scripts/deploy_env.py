#!/usr/bin/env python3

import os
import sys
from time import strftime, sleep
import boto3
from botocore.exceptions import ClientError
from argparse import ArgumentParser

boto3.setup_default_session(region_name='eu-central-1')

def upload_to_s3(artifact):
    """
    Uploads an artifact to Amazon S3
    """
    try:
        client = boto3.client('s3')
    except ClientError as err:
        print("Failed to create boto3 client.\n" + str(err))
        return False

    try:
        client.put_object(
            Body=open(artifact, 'rb'),
            Bucket=args.bucket_name,
            Key=BUCKET_KEY
        )
    except ClientError as err:
        print("Failed to upload artifact to S3.\n" + str(err))
        return False
    except IOError as err:
        print("Failed to access artifact.zip in this directory.\n" + str(err))
        return False

    return True

def version_exists():
    """
    Check if an application version exists in AWS Elastic Beanstalk
    """
    try:
        client = boto3.client('elasticbeanstalk')
    except ClientError as err:
        print("Failed to create boto3 client.\n" + str(err))
        return False

    try:
        response = client.describe_application_versions(
            ApplicationName=args.application_name,
            VersionLabels=[VERSION_LABEL],
        )
    except ClientError as err:
        print("Error checking application version.\n" + str(err))
        sys.exit(1)
    try:
        if response['ResponseMetadata']['HTTPStatusCode']:
            return len(response.get('ApplicationVersions'))>0
        else:
            print(response)
            sys.exit(1)
    except (KeyError, TypeError) as err:
        print(str(err))
        sys.exit(1)


def create_new_version():
    """
    Creates a new application version in AWS Elastic Beanstalk
    """
    try:
        client = boto3.client('elasticbeanstalk')
    except ClientError as err:
        print("Failed to create boto3 client.\n" + str(err))
        return False

    try:
        response = client.create_application_version(
            ApplicationName=args.application_name,
            VersionLabel=VERSION_LABEL,
            Description='New build from Bitbucket',
            SourceBundle={
                'S3Bucket': args.bucket_name,
                'S3Key': BUCKET_KEY
            },
            Process=True
        )
    except ClientError as err:
        print("Failed to create application version.\n" + str(err))
        return False

    try:
        if response['ResponseMetadata']['HTTPStatusCode'] == 200:
            return True
        else:
            print(response)
            return False
    except (KeyError, TypeError) as err:
        print(str(err))
        return False

def deploy_new_version():
    """
    Deploy a new version to AWS Elastic Beanstalk
    """
    try:
        client = boto3.client('elasticbeanstalk')
    except ClientError as err:
        print("Failed to create boto3 client.\n" + str(err))
        return False

    try:
        response = client.update_environment(
            ApplicationName=args.application_name,
            EnvironmentName=args.env_name,
            VersionLabel=VERSION_LABEL,
        )
    except ClientError as err:
        print("Failed to update environment.\n" + str(err))
        return False

    print(response)
    return True

def main():
    " Your favorite wrapper's favorite wrapper "
    if not version_exists():
        if not upload_to_s3(ZIP_FILE):
            sys.exit(1)
        if not create_new_version():
            sys.exit(1)
    # Wait for the new version to be consistent before deploying
    sleep(5)
    if not deploy_new_version():
        sys.exit(1)

def parse_args():
    """
    Parses command line arguments

    Returns:
        ({string}, {string}, {string}) -- (service name, cluster name, image)
    """
    parser = ArgumentParser(description="Deploys new service to beanstalk")
    parser.add_argument("-c", "--commit-id",
                        dest="commit_id",
                        help="commit_id that needs to be deployed.",
        #                required=True,
                        )
    parser.add_argument("-a", "--application_name",
                        dest="application_name",
                        help="beanstalk application name",
                        default='rapidassurance',)
    parser.add_argument("-e", "--env_name",
                        dest="env_name",
                        help="beanstalk environment name",
                        default='rapidassurance-uat',)
    parser.add_argument("-b", "--bucket_name",
                        dest="bucket_name",
                        help="S3 bucket to upload application version to",
                        default='elasticbeanstalk-eu-central-1-969799722025',)    
    args = parser.parse_args()
    return args


if __name__ == "__main__":
    args = parse_args()    
    VERSION_LABEL = f'{args.application_name}-{args.commit_id}'
    ZIP_FILE = f'{VERSION_LABEL}.zip'
    BUCKET_KEY = args.application_name + '/' + ZIP_FILE
    print(VERSION_LABEL)
    print(BUCKET_KEY)
    main()