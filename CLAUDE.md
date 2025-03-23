# EasyAppointments Coding Guidelines

## Commands
- **Install**: `npm install && composer install`
- **Build**: `npm run build`
- **Watch**: `npm start`
- **Tests**: `composer test` (runs PHPUnit)
- **Docs**: `npm run docs`

## Code Style
- **PHP**: 4-space indentation, PascalCase for classes (e.g., `Appointments`), camelCase for methods
- **JS/CSS**: Follow existing patterns with both full and .min versions
- **Types**: Use PHPDoc `@param` and `@return` for type hinting 
- **Errors**: Use try/catch with detailed exceptions; return JSON for AJAX errors
- **Models**: Suffix with `_model` (e.g., `Appointments_model`)
- **Controllers**: Match URL structure; prefix AJAX endpoints with `ajax_`

## Architecture
- Follow CodeIgniter 3.x MVC conventions
- Extend core `EA_Controller` and `EA_Model` classes
- API follows RESTful patterns in `api/v1/` namespace
- Services belong in `engine/` directory

## Documentation
- Use DocBlocks for classes and methods
- Include `@since` annotations for versioning