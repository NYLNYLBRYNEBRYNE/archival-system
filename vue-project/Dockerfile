# Use Node 20 (matching your engines config)
FROM node:20

# Set working directory
WORKDIR /app

# Copy package.json and package-lock.json first (for better caching)
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the app
COPY . .

# Expose Vite port
EXPOSE 5173

# Start the dev server accessible to the network
CMD ["npm", "run", "dev", "--", "--host"]