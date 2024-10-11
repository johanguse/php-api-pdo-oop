# Product API

This is a simple RESTful API for managing products. Follow the instructions below to set up and use the API.

### Todo
- [ ] Add caching mechanism
- [ ] Add logging mechanism
- [ ] Add unit tests
- [ ] Add swagger documentation
- [ ] Add rate limiting
- [ ] Add CORS
- [ ] Add health check endpoint
- [ ] Add CI/CD pipeline
- [ ] Deploy to cloud

## Setup

1. Clone this repository to your local machine.
2. Ensure you have PHP and MySQL installed on your system.
3. Create a new MySQL database for this project.
4. Update the database configuration in `config/database.php` with your database credentials.
5. Import the database schema (if not using the seeder).

## Running the Seeder

To populate your database with sample data, follow these steps:

1. Open a terminal and navigate to the project root directory.
2. Run the following command:

   ```php
   php seed.php
   ```
   Or open `seed.php` file on your project URL (e.g., `http://localhost/your-project-folder/seed.php`)

3. The seeder will create the necessary table and insert sample product data.

## API Endpoints

The following endpoints are available:

- GET `/api/v1/products` - Retrieve all products (with pagination)
- GET `/api/v1/product/{id}` - Retrieve a specific product by ID
- POST `/api/v1/product` - Create a new product
- PUT `/api/v1/product/{id}` - Update an existing product
- DELETE `/api/v1/product/{id}` - Delete a product by ID

### Pagination

The API supports pagination for the `/api/v1/products` endpoint. You can use the following query parameters:

- `page`: The page number you want to retrieve (default: 1)
- `per_page`: The number of items per page (default: 10)

Example usage:

`http://localhost/your-project-folder/api/v1/products?page=2&per_page=5`

This will return the second page with 5 items per page.
The API response for paginated requests includes metadata about the pagination:

```json
{
"data": [
  // Array of product objects
  ],
  "meta": {
    "current_page": 2,
    "per_page": 15,
    "total_count": 100,
    "total_pages": 7
  }
}
```

## Testing with Postman

1. Open Postman.
2. Set the base URL to your local server (e.g., `http://localhost/your-project-folder/api/v1`).
3. Use the following requests to test the API:

   - GET All Products:
     - Method: GET
     - URL: `{{base_url}}/products`
  
   - GET All Products with Pagination:
     - Method: GET
     - URL: `{{base_url}}/products?page=2&per_page=5`

   - GET Single Product:
     - Method: GET
     - URL: `{{base_url}}/product/1`

   - Create Product:
     - Method: POST
     - URL: `{{base_url}}/product`
     - Body (raw JSON):
       ```json
       {
         "name": "New Product",
         "description": "Product description",
         "price": 19.99,
         "category_id": 1
       }
       ```

   - Update Product:
     - Method: PUT
     - URL: `{{base_url}}/product/1`
     - Body (raw JSON):
       ```json
       {
         "name": "Updated Product",
         "description": "Updated description",
         "price": 24.99,
         "category_id": 2
       }
       ```

   - Delete Product:
     - Method: DELETE
     - URL: `{{base_url}}/product/1`

## Testing with cURL

You can also use cURL to test the API from the command line:

1. Get all products:
   ```bash
   curl -X GET http://localhost/your-project-folder/api/v1/products
   ```

2. Get all products with pagination:  
   ```bash
   curl -X GET http://localhost/your-project-folder/api/v1/products?page=2&per_page=5
   ```

3. Get a single product:
   ```bash
   curl -X GET http://localhost/your-project-folder/api/v1/product/1
   ```

4. Create a new product:
   ```bash
   curl -X POST http://localhost/your-project-folder/api/v1/product \
   -H "Content-Type: application/json" \
   -d '{"name":"New Product","description":"Product description","price":19.99}'
   ```

5. Update a product:
   ```bash
   curl -X PUT http://localhost/your-project-folder/api/v1/product/1 \
   -H "Content-Type: application/json" \
   -d '{"name":"Updated Product","description":"Updated description","price":24.99}'
   ```

6. Delete a product:
   ```bash
   curl -X DELETE http://localhost/your-project-folder/api/v1/product/1
   ```

Remember to replace `http://localhost/your-project-folder` with the actual URL where your API is hosted.
