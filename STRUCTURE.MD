``` text
├── .gitignore               # Specifies files and directories to be ignored by Git
├── app/
│   ├── config/              # Configuration files for the application
│   │   └── db.php           # Database connection settings
│   ├── controllers/         # Handles user requests and returns responses
│   │   ├── AuthController.php      # Manages user authentication (login, logout)
│   │   ├── DashboardController.php # Displays the user dashboard
│   │   ├── ProjectController.php   # Handles project-related actions (create, view, update)
│   │   ├── TaskController.php      # Manages task-related actions (create, view, update)
│   │   └── UserController.php      # Handles user management (create, view, edit)
│   ├── models/              # Represents data structures and business logic
│   │   ├── Project.php      # Defines the Project data model
│   │   ├── Task.php         # Defines the Task data model
│   │   └── User.php         # Defines the User data model
│   └── views/               # PHP templates for rendering HTML
│       ├── auth/            # Views related to authentication
│       │   └── login.php    # Login form page
│       ├── dashboard/       # Views for the main dashboard
│       │   └── index.php    # Main dashboard view
│       ├── layouts/         # Common template parts
│       │   ├── footer.php   # Footer for the site
│       │   ├── header.php   # Header for the site (navigation, etc.)
│       │   └── pagination.php # View for pagination controls
│       ├── projects/        # Views for managing projects
│       │   ├── form.php     # Form for creating/editing a project
│       │   └── index.php    # List of all projects
│       ├── tasks/           # Views for managing tasks
│       │   ├── form.php     # Form for creating/editing a task
│       │   └── index.php    # List of all tasks
│       └── users/           # Views for managing users
│           ├── form.php     # Form for creating/editing a user
│           └── index.php    # List of all users
├── database.sql             # SQL script for creating the database schema
├── public/                  # Web root; publicly accessible files
│   ├── assets/              # Static assets like CSS and JS
│   │   ├── css/             # Stylesheets
│   │   │   ├── bootstrap.min.css # Bootstrap framework CSS
│   │   │   ├── login.css    # Custom CSS for the login page
│   │   │   └── style.css    # Main custom stylesheet
│   │   └── js/              # JavaScript files
│   │       ├── bootstrap.bundle.min.js # Bootstrap framework JS
│   │       ├── jquery-3.7.0.min.js     # jQuery library
│   │       └── moment.min.js         # Moment.js library for date/time manipulation
│   └── index.php            # The front controller that routes all requests
├── README.md                # Project documentation and setup instructions
├── system/                  # Core framework files
│   ├── core/                # Essential framework components
│   │   ├── Controller.php   # Base controller class
│   │   ├── Model.php        # Base model class
│   │   └── Router.php       # Handles URL routing
│   └── libraries/           # Helper classes and libraries
│       └── Auth.php         # Authentication library
└── uploads/                 # Storage for user-uploaded files
└── tasks/               # Uploads specifically for task attachments

```