# Askend Filter system test work
-Used symfony docker with caddy and frankenphp in order to boot it in docker.
-PostgreSQL for database
-React for frontend

### How to boot up the program
1. Launch Docker
2. Move into the root folder of the project
3. Create an .env file based on the .env.example
4. Start the container with docker-compose up --build (-d)
5. Go to http://localhost:3000/filters in your browser

### Endpoints
1. GET /filters - Display a list of all the filters
2. POST /filters - Add new filter
3. GET /filters/{id} - Get filter based on id
4. PUT /filters/{id} - Update filter based on id
5. DELETE /filters/{id} - Delete filter based on id
