# Rapid Assurance


## Git Workflow

- Before starting development you need to create a new branch from master.
- First checkout the `master` branch with `git checkout master`.
- Then do a fresh pull to make sure all changes are upto-date with `git pull`.
- Then create a new branch with `git checkout -b feature/my-username/the-feature-this-branch-will-have`. Types of commit can be feature, fix, refactoring, or task etc.
- Start development.
- Once you're done with the development stage your changes to be added to git with `git add filename` to add only specific file or `git add .` to add all files at once.
- Once you've added the files to git, you need to commit them. You can commit them using `git commit -m "Some useful message regarding the changes you added in the previous step"`
- Once you've committed the changes, you will need to push your branch to github using the branch name that you've currently checked out using `git push -u origin feature/my-username/the-feature-this-branch-will-have`
- Now you can go to github and create a pull request.
- Once PR is created, send the link to get it approved and merged after testing.