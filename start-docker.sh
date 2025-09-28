#!/bin/bash

echo "🚀 Starting Laravel Class Scheduler with Docker..."

# Check if .env exists, if not copy from .env.docker
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.docker template..."
    cp .env.docker .env
    echo "⚠️  Please update your .env file with proper values before continuing"
    echo "💡 Run 'php artisan key:generate' after starting containers to generate APP_KEY"
fi

# Build and start containers
echo "🐳 Building and starting Docker containers..."
docker-compose -f docker-compose.prod.yml up --build -d

echo "⏳ Waiting for containers to be ready..."
sleep 10

# Generate application key if needed
if docker-compose -f docker-compose.prod.yml exec -T app grep -q "GENERATE_NEW_KEY_HERE" .env; then
    echo "🔑 Generating application key..."
    docker-compose -f docker-compose.prod.yml exec -T app php artisan key:generate --force
fi

echo "✅ Setup complete!"
echo "🌐 Your application should be available at: http://localhost"
echo ""
echo "📋 Useful commands:"
echo "  - View logs: docker-compose -f docker-compose.prod.yml logs -f"
echo "  - Stop containers: docker-compose -f docker-compose.prod.yml down"
echo "  - Restart: docker-compose -f docker-compose.prod.yml restart"
