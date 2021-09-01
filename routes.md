admin_home
----------

- Path: /admin
- Path Regex: {^/admin$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: ANY
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\AdminController::home
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_category_list
-------------------

- Path: /admin/categories
- Path Regex: {^/admin/categories$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CategoryController::list
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_category_show
-------------------

- Path: /admin/categories/{id}/show
- Path Regex: {^/admin/categories/(?P<id>\d+)/show$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CategoryController::show
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_category_create
---------------------

- Path: /admin/categories/create
- Path Regex: {^/admin/categories/create$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CategoryController::create
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_category_edit
-------------------

- Path: /admin/categories/{id}/edit
- Path Regex: {^/admin/categories/(?P<id>\d+)/edit$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CategoryController::edit
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_category_delete
---------------------

- Path: /admin/categories/{id}/delete
- Path Regex: {^/admin/categories/(?P<id>\d+)/delete$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CategoryController::delete
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_collaborator_list
-----------------------

- Path: /admin/collaborators
- Path Regex: {^/admin/collaborators$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CollaboratorController::list
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_collaborator_show
-----------------------

- Path: /admin/collaborators/{id}/show
- Path Regex: {^/admin/collaborators/(?P<id>\d+)/show$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CollaboratorController::show
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_collaborator_create
-------------------------

- Path: /admin/collaborators/create
- Path Regex: {^/admin/collaborators/create$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CollaboratorController::create
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_collaborator_edit
-----------------------

- Path: /admin/collaborators/{id}/edit
- Path Regex: {^/admin/collaborators/(?P<id>\d+)/edit$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CollaboratorController::edit
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_collaborator_delete
-------------------------

- Path: /admin/collaborators/{id}/delete
- Path Regex: {^/admin/collaborators/(?P<id>\d+)/delete$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CollaboratorController::delete
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_collaborator_desactive
----------------------------

- Path: /admin/collaborators/{id}/desactive
- Path Regex: {^/admin/collaborators/(?P<id>\d+)/desactive$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CollaboratorController::desactive
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_collaborator_active
-------------------------

- Path: /admin/collaboratorss/{id}/active
- Path Regex: {^/admin/collaboratorss/(?P<id>\d+)/active$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CollaboratorController::active
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_comment_list
------------------

- Path: /admin/comments
- Path Regex: {^/admin/comments$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CommentController::list
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_comment_show
------------------

- Path: /admin/comments/{id}/show
- Path Regex: {^/admin/comments/(?P<id>\d+)/show$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CommentController::show
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_comment_create
--------------------

- Path: /admin/comments/create
- Path Regex: {^/admin/comments/create$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CommentController::create
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_comment_edit
------------------

- Path: /admin/comments/{id}/edit
- Path Regex: {^/admin/comments/(?P<id>\d+)/edit$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CommentController::edit
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_comment_delete
--------------------

- Path: /admin/comments/{id}/delete
- Path Regex: {^/admin/comments/(?P<id>\d+)/delete$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\CommentController::delete
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_event_list
----------------

- Path: /admin/events
- Path Regex: {^/admin/events$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\EventController::list
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_event_show
----------------

- Path: /admin/events/{id}/show
- Path Regex: {^/admin/events/(?P<id>\d+)/show$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\EventController::show
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_event_create
------------------

- Path: /admin/events/create
- Path Regex: {^/admin/events/create$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\EventController::create
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_event_edit
----------------

- Path: /admin/events/{id}/edit
- Path Regex: {^/admin/events/(?P<id>\d+)/edit$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\EventController::edit
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_event_delete
------------------

- Path: /admin/events/{id}/delete
- Path Regex: {^/admin/events/(?P<id>\d+)/delete$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\EventController::delete
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_event_desactive
---------------------

- Path: /admin/events/{id}/desactive
- Path Regex: {^/admin/events/(?P<id>\d+)/desactive$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\EventController::desactive
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_event_active
------------------

- Path: /admin/events/{id}/active
- Path Regex: {^/admin/events/(?P<id>\d+)/active$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\EventController::active
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_user_list
---------------

- Path: /admin/users
- Path Regex: {^/admin/users$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\UserController::list
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_user_show
---------------

- Path: /admin/users/{id}/show
- Path Regex: {^/admin/users/(?P<id>\d+)/show$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\UserController::show
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_user_create
-----------------

- Path: /admin/users/create
- Path Regex: {^/admin/users/create$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\UserController::create
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_user_edit
---------------

- Path: /admin/users/{id}/edit
- Path Regex: {^/admin/users/(?P<id>\d+)/edit$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\UserController::edit
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_user_delete
-----------------

- Path: /admin/users/{id}/delete
- Path Regex: {^/admin/users/(?P<id>\d+)/delete$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\UserController::delete
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_user_desactive
--------------------

- Path: /admin/users/{id}/desactive
- Path Regex: {^/admin/users/(?P<id>\d+)/desactive$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\UserController::desactive
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


admin_user_active
-----------------

- Path: /admin/users/{id}/active
- Path Regex: {^/admin/users/(?P<id>\d+)/active$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\Admin\UserController::active
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_event_join
--------------

- Path: /event/{id}/join
- Path Regex: {^/event/(?P<id>\d+)/join$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\AttendantController::join
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_event_leave
---------------

- Path: /event/{id}/leave
- Path Regex: {^/event/(?P<id>\d+)/leave$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\AttendantController::leave
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_category_list
-----------------

- Path: /categories
- Path Regex: {^/categories$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\CategoryController::list
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_event_list
--------------

- Path: /events
- Path Regex: {^/events$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\EventController::list
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_event_create
----------------

- Path: /events/create
- Path Regex: {^/events/create$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\EventController::create
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_event_edit
--------------

- Path: /events/{id}/edit
- Path Regex: {^/events/(?P<id>\d+)/edit$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\EventController::edit
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_event_show
--------------

- Path: /events/{id}/show
- Path Regex: {^/events/(?P<id>\d+)/show$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\EventController::show
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_event_delete
----------------

- Path: /events/{id}/delete
- Path Regex: {^/events/(?P<id>\d+)/delete$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\EventController::delete
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_friend_add
--------------

- Path: /profile/friends/{id}/add
- Path Regex: {^/profile/friends/(?P<id>\d+)/add$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\FriendshipController::addFriend
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_friend_delete
-----------------

- Path: /profile/friends/{id}/delete
- Path Regex: {^/profile/friends/(?P<id>\d+)/delete$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\FriendshipController::deleteFriend
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_home
--------

- Path: /
- Path Regex: {^/$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\HomeController::home
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_contact_contact
-------------------

- Path: /contact
- Path Regex: {^/contact$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\MainController::contact
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_legal_notice
----------------

- Path: /legal-notice
- Path Regex: {^/legal\-notice$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\MainController::legalNotice
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_privacy_policy
------------------

- Path: /privacy_policy
- Path Regex: {^/privacy_policy$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\MainController::privacyPolicy
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_cgu
-------

- Path: /cgu
- Path Regex: {^/cgu$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\MainController::cgu
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app__main_team
--------------

- Path: /team
- Path Regex: {^/team$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\MainController::team
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_profile_show
----------------

- Path: /profile/{id}
- Path Regex: {^/profile/(?P<id>\d+)$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\ProfileController::show
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_profile_profile
-------------------

- Path: /profile
- Path Regex: {^/profile$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\ProfileController::profile
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_profile_edit
----------------

- Path: /profile/edit
- Path Regex: {^/profile/edit$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\ProfileController::edit
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_profile_events
------------------

- Path: /profile/events
- Path Regex: {^/profile/events$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\ProfileController::showEvents
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_register
------------

- Path: /register
- Path Regex: {^/register$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: ANY
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\RegistrationController::register
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_verify_email
----------------

- Path: /verify/email
- Path Regex: {^/verify/email$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: ANY
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\RegistrationController::verifyUserEmail
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_report_user
---------------

- Path: /report/user/{id}
- Path Regex: {^/report/user/(?P<id>\d+)$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\ReportController::user
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_report_event
----------------

- Path: /report/event/{id}
- Path Regex: {^/report/event/(?P<id>\d+)$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\ReportController::event
- Requirements: 
    - `id`: \d+
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_forgot_password_request
---------------------------

- Path: /reset-password
- Path Regex: {^/reset\-password$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: ANY
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\ResetPasswordController::request
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_check_email
---------------

- Path: /reset-password/check-email
- Path Regex: {^/reset\-password/check\-email$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: ANY
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\ResetPasswordController::checkEmail
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_reset_password
------------------

- Path: /reset-password/reset/{token}
- Path Regex: {^/reset\-password/reset(?:/(?P<token>[^/]++))?$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: ANY
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\ResetPasswordController::reset
    - `token`: NULL
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_login
---------

- Path: /login
- Path Regex: {^/login$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: GET|POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\SecurityController::login
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true


app_logout
----------

- Path: /logout
- Path Regex: {^/logout$}sDu
- Host: ANY
- Host Regex: 
- Scheme: ANY
- Method: ANY
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: App\Controller\SecurityController::logout
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Symfony\Component\Routing\RouteCompiler
    - `utf8`: true
