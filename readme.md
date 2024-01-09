# CarReviews
CarReviews is a project made with Symfony 6 and API Platform 3.

## Installation

Make sure you have docker-compose installed. Then run:

```bash
make up
```
In order to initialize the project packages and database, run:

```bash
make init
```

The project is ready, visit http://localhost:8000/api for API Platform documentation.

## Overview

In this project, we have a Car and a Review entity. RESTful CRUD APIs are created for these two entities. Also, there is a /cars/{car_id}/latest-reviews, customizable to return any number of Reviews with their rating higher than a certain value.

## Test

This project uses API (end to end) tests, to make sure the endpoints work well. To run the test, run:

```bash
make test
```

(Please notice that notices and errors are not silenced during the test, so the output may be a bit messy.)

## Database

This project uses PostgreSQL version 14.

## Data Fixtures

Dummy data is prepared in the form of data fixtures and will be inserted into the database, during the make init command. Also in order to run the fixtures separately, run:

```bash
make fixture
```

## License

[MIT](https://choosealicense.com/licenses/mit/)
