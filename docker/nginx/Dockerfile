# Stage 1: Build assets with Node.js
FROM node:22-alpine AS assets

WORKDIR /var/www

# Copy package files
COPY package*.json /var/www/

# Install dependencies
RUN npm install

# Copy Laravel application
COPY . /var/www

# Build assets
RUN npm run build

# Stage 2: Nginx production image
FROM nginx:alpine

# Copy custom Nginx configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/conf.d/ /etc/nginx/conf.d/

# Copy Laravel public assets from builder
COPY --from=assets /var/www/public /var/www/public

WORKDIR /var/www/public

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
