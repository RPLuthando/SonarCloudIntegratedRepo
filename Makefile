APPLICATION_NAME:=rapidassurance
COMMIT_ID:=${shell git rev-parse --short HEAD}

setup:
	chmod +x deployment_scripts/*.py

run-tests:
	echo "Replace run-tests in Makefile with actual test commands to execute it here."

package: setup
	rm -rf ${APPLICATION_NAME}-*.zip
	zip -rq ${APPLICATION_NAME}-${COMMIT_ID}.zip * -x database_dump/\* .env *.zip

deploy-uat: package
	deployment_scripts/deploy_env.py --commit-id=${COMMIT_ID} --env_name uat-rapidassurance

deploy-prod: package
	deployment_scripts/deploy_env.py --commit-id=${COMMIT_ID} --env_name payu-rapidassurance