# COP4331C-Small-Project

## Local Setup

### Requirements
- PHP 8.4.16 (cli) must be installed: [Install Guide](https://www.php.net/manual/en/install.php)
- Docker Desktop must be installed: [Install Guide](https://docs.docker.com/desktop/)

### Steps
1. Download the code for the project. You can get a zip file of it from the green code drop down.
2. Open a terminal window in ./Server
3. Run ``docker run --name test-sql -e MYSQL_ROOT_PASSWORD=root -p 3306:3306 -d mysql:latest`` as an administrator
4. Run ``docker exec test-sql mysql -uroot -proot -e "CREATE DATABASE test;"`` as an administrator
5. Run ``php -S localhost:8000``
   1. You might encounter an issue when loading the page, "Connection failed...", this is because docker set your container port to something else.
   2. Run ``docker ps``, then notice the "PORTS" column
   3. If you see 0.0.0.0:OTHERPORT -> 3306/tcp, your port was set to OTHERPORT for access.
   4. Go to /Server/components/db.php and change the string next to $port to OTHERPORT.

---

## To-Do
Please review and update this section with any additional tasks required to complete the project. This may be migrated to Trello later if needed.

- [x] Write SQL for creating user accounts in `Server/components/register.php`
- [ ] Develop Login API that serves cookies on response
- [ ] Complete register API (must return cookie to allow user access to contacts upon registration)
- [ ] Build Contacts API to display all contacts (paginated)
- [ ] Implement Search API (paginated)
- [ ] Create delete API

---

## REST API
See apiDocs.md, but it will move to Swaggerhub whenever that's up.

---

## AI Assistance Disclosure

This project was developed with assistance from generative AI tools:

- **Tool**: Qwen3 8b (Alibaba Cloud, lmstudio.ai/models/qwen/qwen3-8b)
- **Dates**: January 22nd, 2026
- **Scope**: Boiler plate for the README.md file sections "To-Do" and "REST API".
- **Use**: Generated the specified format for the README.md while providing reasoning for changes to sentence format if needed. Sections were edited and the "Local Setup" section was written manually to ensure its quality. 

No code, commands, or information was generated. The format itself is the only thing the model generated and that was edited as well.