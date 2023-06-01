## PHP Backend for Portfolio Site

A PHP backend API server for my portfilio project, to provide the endpoints and interface with the MySQL DB to store and retreive all of the content.

Just beyond MVP, so still some work to do and additions to be made.

Built using: 

PHP 8.1+  
MySQL (Was originally intended for MariaDB and used 'RETURNING', changed very last minute due to availability :( ).  

Libraries via Composer  
Guzzle  
phpdotenv  
Google API client  
PHPUnit 10.x   

.env.example copy to .env 
```
DB_HOST='localhost'     ## hostname of server hosting DB
DB_USER=''              ## username for DB access  
DB_PASS=''              ## password for DB access
DB_NAME=''              ## name of DB to access  
SHOW_ERRORS=false       ## Show errors true / false  - false redirects all errors to a file in logs/
GOOGLE_CLIENT_ID=''     ## CLient ID for Google GIS
GOOGLE_CLIENT_SECRET='' ## Secret to validate JWT passed from user / GIS
RC_SECRET=''            ## ReCaptcha secret
ALLOWED_HOSTS=''        ## Comma separated list of hosts to allow CORS (admin panel and front end required)
FILTER_IP=''            ## Filter a specific IP from the logs. 
DOMAIN=''               ## Domain the API is hosted at - used in JWTs
AUDIENCE=''             ## Domain the admin app is hosted at - used in JWTs
ACCESS_KEY=             ## Key used to sign the access token
REFRESH_KEY=            ## Key used to sign the refresh token
```

Mirrored to GitHub from my (self hosted) Gitea  instance.  

