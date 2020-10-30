[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)

# CredPal technical assessment instruction

Using Laravel/Vue create a simple application that allows a user do the following:

1.    Create an account using a referral system, kindly auto generate referral codes during account creation. (DONE)

2.    Earn 1,000 upon account creation if the referral code is used. (DONE)

3.    View wallet balances. (DONE)

4.    Simulate instant and future account transfers. (DONE)

5.    Simulate KYC levels for transfer limits. A user with a valid ID should be able to transfer over 50,000 AND one without a valid ID shouldn't. (DONE)

This would ideally be thoroughly documented with complete test coverage, kindly host the code submission on bitbucket/github and provide a clear setup instruction within the README.md file.

The deadline for submission is Friday 30th October 2020, on or before 12:00pm.

All the best!

# Project Description (Pull the main branch)

Used lumen(laravel) for the backend and vue cli for the frontend. I believe when you specified the laravel/Vue combination. This is what you meant. Implemented all the required features and did a full test coverage.

# Tools Needed
1. PHP (7.2)
2. MYSQL (5.7)
3. cli or npm

Pull from the main branch

# STEP 1 : DATABASE INSTRUCTION
CREATE a new database called credpal. User the default user root and no password. If you have to change any of this values. Change the values in the .env file inside the BACKEND folder. Import the credpal.sql and you done.

# STEP 2 : BACKEND
Include .env file to make it easier with installation (TAKE NOTE). If you need to change database related values do it in the .env inside the BACKEND folder. To run the backend use the command
	 php -S localhost:9000 -t public 

It is important you run on port 9000, the frontend is listening on that port.

# STEP 3 : BACKEND (test)
Run full test on the backend with the command but from inside the BACKEND directory 
	vendor/bin/phpunit

# STEP 4 : BACKEND (Swagger API)
Run the command to generate and publish the swagger pages. 
	php artisan swagger-lume:generate
	php artisan swagger-lume:publish

The swagger json url is localhost:9000:/api and the swagger DOCS is localhost:9000/api/documentation 


# STEP 5 : FRONTEND
Used vue cli for the frontend. You need to install vue cli or npm. After installation run the two command
	npm install
	npm run serve

It will start the frontend on port localhost:8080. Navigate to the url in your browser and signup. After login. If your internet is connected. An SMS will be sent to welcome you to the platform. I deativated the mobile confirmation feature and knowing you most likely don't have SMTP configured on your local machine. Deactivate the email confirmation features also. So just login and all is set. Used vuex as a store management so all global values are there.
	

