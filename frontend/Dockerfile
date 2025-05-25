FROM node:18

# Make sure devDependencies (like Vite) are installed
ENV NODE_ENV=development

# Set the working directory to the container's root context of your code
WORKDIR /frontend

# Copy only package files first to leverage Docker cache
COPY package.json vite.config.js ./

RUN npm install

# Now copy everything else
COPY . .

EXPOSE 3000

CMD ["npm", "run", "dev"]