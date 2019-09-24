# MamiKos API Test

## About Project
MamiKos is an app where user can search kost for room that have been added by owner.

Also, user can ask about room availability using credit system.

Regular user will have 20 credit and premium user will have 40 credit per month.

## Project Requirement
1. Owner is allowed to add more than 1 kost
2. Owner is allowed to update and delete his/her kost
3. User can see kost list that have been added
4. User search kost by name, city, or price
5. User can sort the list by price
6. User can see kost detail
7. User ask about room availability using their credit (5 credit per action)

# Setup Project On Your Local Machine

## Prerequisites
1. Apache
2. PHP 7.2
3. MySQL
4. Git

## Setup Guide
1. Make sure you install all the Prerequisite
2. Clone the project from github to your directory

`git@github.com:tyanosaurusrex/mamikos-api-test.git`

3. Copy the env.example file and rename the new one with .env. Update if needed

4. Setup the database by create new database on you local machine, and run these commands

`php artisan migrate`

5. Run the project using this command

`php artisan serve`

and the project will be running on `http://localhost:8000`

6. If there's an error with Personal Access Client, run

`php artisan passport:install`

# API List

## Auth /api/

**Register User**

> POST /api/register

Body:
```
{
	"name": "owner1",
	"email": "owner1@mail.com",
	"password": "owner1",
	"confirm_password": "owner1",
	"role": "1"
}
```
**Login User**

> POST /api/login

Body:
```
{
	"email": "user0@mail.com",
	"password": "user0"
}
```
**Logout User**

> POST /api/logout

Authorization: Bearer Token


## User /api/users/

**User Detail**
> POST /api/users/details

Authorization: Bearer Token

**Upgrade User Status**
> PUT /api/users/upgrade_status

Authorization: Bearer Token

## Kost /api/kosts/

**Show Kosts List with Query Search**
> GET /api/kosts?city={city}&sort={sort}&by={sortby}

**Show Kost Detail**
> GET /api/kosts/{id}

**Create New Kost**
> POST /api/kosts

Authorization: Bearer Token
Body:
```
{
	"name": "Kost B",
	"city": "yogya",
	"room_length": 3,
	"room_width": 3,
	"available_rooms": 2,
	"price": 700000
}
```

**Update Kost Detail**
> PUT /api/kosts/{id}

Authorization: Bearer Token
Body:
```
{
	"name": "Kost B",
	"city": "yogya",
	"room_length": 3,
	"room_width": 3,
	"available_rooms": 2,
	"price": 700000
}
```

**Delete Kost**
> DELETE /api/kosts/{id}

Authorization: Bearer Token

## User Activity /api/activities

**Ask Question**
> POST /api/activities/ask

Authorization: Bearer Token
Body: 
```
{
	"recipient_id": 3,
	"activity": "Ask",
	"credit_usage": 5
}
```