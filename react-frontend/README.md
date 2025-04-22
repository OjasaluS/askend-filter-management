# React Frontend Project

This is the React front-end application that integrates with the existing Symfony API project. It is designed to run alongside the Symfony backend using Docker.

## Getting Started

To get started with the React front-end, follow these steps:

1. **Clone the Repository**: 
   Clone the repository to your local machine.

   ```bash
   git clone <repository-url>
   cd askend
   ```

2. **Install Dependencies**: 
   Navigate to the `react-frontend` directory and install the necessary dependencies.

   ```bash
   cd react-frontend
   npm install
   ```

3. **Running the Application**: 
   You can run the application using Docker. Make sure you have Docker and Docker Compose installed.

   ```bash
   docker-compose up
   ```

   This command will start both the Symfony API and the React front-end.

4. **Accessing the Application**: 
   Once the containers are up and running, you can access the React application at `http://localhost:3000` (or the port specified in your `docker-compose` configuration).

## Development

For development, you can make changes to the React components located in the `src` directory. The application supports hot reloading, so changes will be reflected in the browser without needing to refresh.

## Building for Production

To build the React application for production, run the following command:

```bash
npm run build
```

This will create an optimized build of the application in the `build` directory.

## API Integration

The React front-end communicates with the Symfony API. Ensure that the API is running and accessible. You can configure the API endpoint in the environment variables or directly in the code.

## Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue for any enhancements or bug fixes.

## License

This project is licensed under the MIT License. See the LICENSE file for more details.