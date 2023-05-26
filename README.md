# GroupMProject - HallAmi

#### Testing

In order to run the tests for the deployment, first you must download and deploy the code root and testing root from
the main GitHub branch. Once deployed, use a web browser to navigate to  ‘\<Your deployment>/testing%20root/‘ and all
current tests will show up. Each test will show as passed or display an error level between 0 and 5, depending on how
crucial the error is. If you wish to add more tests there is a README file within ‘testing root’ outlining this process.

#### Deployment

In order to deploy the website, you need to complete the following steps
- Copy all the files from the ‘code root‘ in the main GitHub branch to your deployment target.
- Deploy a MySQL database and edit ‘main_model.php’ to point towards your database credentials.
- Run the [Create_Tables.sql](./Database%20Documents/Create_Tables.sql), [Create_Procedures.sql](./Database%20Documents/Create_Procedures.sql) and [Populate_Halls.sql](./Database%20Documents/Populate_Halls.sql) from the [Database Documents](./Database%20Documents) folder.
- Navigate to ‘\<Your deployment\>‘ to get redirected to the homepage.

(Note: The website is designed to run on PHP 7.3)

#### Getting Started (User Guide)

First, while not essential, to fully interact with HallAmi you may wish to create a user account.  
This will enable you to comment, subscribe to and create Blocks, post Rooms and vote.
Also note, at any time you can return to the homepage by clicking the University of Exeter logo at the top of the page.
To do this, click the top right corner that says "login".
Here you are able to register and enter your details. Please make sure you enter a valid University of Exeter email address (@exeter.ac.uk).

Now you are signed up, it's time to explore the site.
You will notice the top right hand corner has changed to your username and a number. This number is your level which is determined by your votes and community points.
Level up by engaging with the community!
If you click on your level, you are able to change settings, view your profile or sign out.
Within settings you are able to change your password or logout.
Within your profile settings you can see your level and your progress towards the next level and a breakdown of your total upvotes and community points.
You can also press the "Send Exe-P" to transfer community points to another user, for instance if they complete a challenge you set.

Now, feel free to click on any one of the three Halls you wish to explore first: Academic, Society or Community.
In the Academic Halls you will see Blocks relating to modules, courses and various other academic topics.
Society Halls are where you can discover Blocks dedicated to current University of Exeter affiliated societies.
Community Halls are for general discussion about whatever you want!
Once you have chosen a Hall, click on a Block you like the sound of, the description will be underneath, or create your own using the "Create Block" button (don't forget to add a description!).

Once you are inside a Block, browse through Rooms that other users have posted and make sure to engage with the community by upvoting Rooms you like and interest you.
If a particular Room interests you, click on it and you will see commments other users have left. You may reply to them by commenting or vote if you wish.
If you have any thoughts on the topic of the Block you are in, post your own Room by pressing the "Create Room" button for others to view, comment and vote on!
Make sure to check out the other Halls too!

It's now time to engage with as many comments and Rooms as you can! Remember to subscribe to Blocks you like by clicking the "Subscribe" button.
This will add the Block to your feed which appears on the sidebar which is accessible by pressing the three horizontal lines in the top left corner of your screen.
You may also post a challenge within a Room and wager your community points. Complete challenges to gain more while interacting with the community.
The "Members" button within a Block displays all current members in order of community points as a leaderboard; keep active in the community to climb to the top!

#### Website Features

Once on the website you will be able to:
- Register an account
- Login to your account
- Access the three different Halls via the Home Page
- Access the Navigation Bar from anywhere on the website
- From the Hall pages you are able to:
  - View and access Blocks in that hall
  - Create new Blocks
- From the Block pages you are able to:
  - View, vote and access Rooms in that Block
  - View the About section of the Block
  - View the members of the block
  - Create new Rooms
  - Subscribe or Unsubscribe from the Block
  - Edit the Block description if you are an admin
- From the Room pages you are able to:
  - View the contents of the Room
  - Vote on the Room
  - View comments in the Room
  - Vote on comments in the Room
  - Comment on the Room
  - Comment on comments
  - Edit the Room content if you are the creator
  - Share the link to the Room
- From the Navigation Bar you are able to:
  - View your User Level
  - Access the Home Page
  - Access the User Page
  - Access the Settings page
  - Log Out
  - Access the Side Bar
- From the Side Bar you are able to:
    - Access the three different Halls
    - View and access all the Blocks you are subscribed to in each Hall
- From the User Page you are able to:
  - View your current level
  - View your current Community and Upvote Exe-P
  - View your progress to the next level
  - Log out
- From the Settings Page you are able to:
  - Change password
  - Log out
- From the Send Exe-P Page you are able to:
  - Send other users some of your community Exe-P


#### Project Structure

For our project we are following the Model View Controller structure, which divides the related program logic into
 three main interconnected elements. As well as the three main elements we also have a number of supporting elements
 . The file structure for the code root is as follows:
- control.php - A php file which deals with all the requests to the website and points to the necessary controller or
 util
- main_model.php - A php file which acts as the main connection to the database, handling all database queries, 
results and errors
- views - A directory containing all the main html, front end elements of the website
- controllers - A directory containing php files that prepare the content to be shown in the views
- models - A directory containing php files, which are called by utils and apis, and contain pre-prepared sql queries
 that can be called and run though the main model
- css - A directory containing all the css files for styling the views
- js - A directory containing all the javaScript files for making the views interactive
- utils - A directory containing php files, which handle data from other internal files (To be called from php files)
- api-controllers - A directory containing php files, to be called from java script, and handle data sourced from models
- assets - A directory containing all the project assets, such as logos and icons

For copyright information see [Licence.txt](./Licence.txt).