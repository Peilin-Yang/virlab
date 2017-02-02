<?php 
  include("include/superHead.php");
  include("include/mysql_connect.php");

  if(!isset($_SESSION['user'])){
      echo "This toolkit is only for register users, please <a href=\"login.php\">Login</a><br/>\n";
      header('Location: login.php');
  } else {
    $uid=$_SESSION['userID'];

    $sql_user = mysqli_connect('p:'.MysqlHost,MysqlUser,MysqlPassword,'users2',MysqlPort);
    $query_user="select userType from user where userID=$uid";
    $results_user=mysqli_query($sql_user,$query_user);
    $row_user=mysqli_fetch_row($results_user);
    $userType=$row_user[0];
    if ($userType != 255) {
      header('Location: login.php');
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The Introduction of Virtual IR Lab">
    <meta name="author" content="Peilin Yang">
    <!--<link rel="shortcut icon" href="../../assets/ico/favicon.ico"> -->

    <title>User Comments</title>

    <!-- Bootstrap core CSS -->
    <link href="static/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="static/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="static/css/json.css" rel="stylesheet">
  </head>

  <body role="document">

  <div class="container">
    <div class="page-header" style="margin-top:10px;">
    </div>
    
    <ul class="nav nav-tabs">
      <li class="active"><a href="#comments" data-toggle="tab">User Comments</a></li>
      <li><a href="#todolist" data-toggle="tab">Highlighted</a></li>    
    </ul>

    <div class="tab-content" style="margin-bottom:20px;">
    <div class="tab-pane fade in active" id="comments">
      <div>
        <table class="table table-bordered table-hover" style="vertical-align:middle;">
            <thead>
                <tr> 
                    <th>userID</th>
                    <th>Positive Comments</th>
                    <th>Suggestions or Bug Reports</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                  <td>96</td>
                  <td class="success comments">
                    <ul>
                      <li>Helpful for people who learn the retrieval function for the first time</li>
                      <li>Help to understand the retrieval function and make it easy to implement the retrieval function</li>
                    </ul>
                  </td>
                  <td class="warning comments"></td>

                </tr>
                <tr>
                  <td>105</td>
                  <td class="success comments">
                    <ul>
                      <li>Overall is fine</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>It will be great if there is a class to go through how to properly use everything and maybe have implemented a few other sample methods</li>
                      <li>The time it took to run different values for the variables for the okapi and pivoted methods took way too long</li>
                      <li>Occasional crashing</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>106</td>
                  <td class="success comments">
                    <ul>
                      <li>I really like such an online engine. It enables us to have direct experience in using retrieval functions</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>It is difficult to figure what exactly results in the different MAP result of the same function applied to different datasets since he datasets and queries are somehow invisible</li>
                      <li>Upload own datasets and queries</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>93</td>
                  <td class="success comments">
                    <ul>
                      <li>It was a rewarding experience</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>Long evaluation time</li>
                      <li>Consistent returning of login errors and database errors</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>103</td>
                  <td class="success comments">
                    <ul>
                      <li>This system was very well designed and implemented</li>
                      <li>The layout is aesthetically pleasing and navigation is well thought out. I especially liked the textbox c compiler design which colored different keywords and variable values as you see on a top end compiler like Microsoft Visual Studio</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>Text box for the compiler is not very wide. It would work much better if it was based on the screen size</li>
                      <li>Make the system run a little faster or at the very least, provide a progress bar on the screen</li>                      
                      <li>When trying to refine functions, if you modify a function, if you attempt to overwrite a function by saving, but cancel the save at the warning dialog, you lose your evaluation results</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>95</td>
                  <td class="success comments">
                    <ul>
                      <li>It was very easy to use</li>
                      <li>Creating and modifying functions, coding errors being displayed immediately, and the ease of running tests were all welcome features. Not needing to set this up on my own system or trying to manage all of the features on my own system was welcome</li>
                      <li>This was probably the quickest I've gotten up and running with programming/experimenting in a class.</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>It would be nice to be able to submit jobs to be run when possible while we continue to work on other functions</li>
                      <li>it would be nice to have a method to export your results into a CSV type file. The columns could be function name, parameter values, MAP, P30</li>              
                      <li>In some cases it was unclear exactly what a variable/statistic we could use actually meant, or where it was appropriate. (example) It would also be a good thing if we could know a little about the actual document sets, some general stats would be useful for us to try to tune parameters without wildly guessing</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>111</td>
                  <td class="success comments">
                    <ul>
                      <li>I liked the system</li>
                      <li>It was really motivating to have the competition!</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>If the evaluations were calculated locally it would be faster and more efficient</li>
                      <li>Seperate the submitted functions for "LeaderBoard" and others for tuning parameters</li>              
                      <li>This will help also at the fact that we have different performance for each collection. So lets assume that we have max for DOE B25 with b=0.41 but max for Robust b=0.42, if we run for both values the system will keep one. We should rename it.</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>107</td>
                  <td class="success comments">
                    <ul>
                      <li>The VIRLab system is quite useful for me to study this course. It is easy to input the different formulas and test the performance</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>The time to execute evaluation is considerable long</li>
                      <li>Sometimes can’t log in or save the functions</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>90</td>
                  <td class="success comments">
                    <ul>
                      <li>I really enjoyed seeing the course concepts in a practical manner</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>The first bug I encounter was, it occurs to me several times that I wrote an IR function and I saved it correctly. Then when I came back for my file system gave me error that it can’t find the content (the following error). So I have to write it again. Warning: file_get_contents(users/90/retFun/OKapiChanged.fung): failed to open stream: No such file or directory in/infolab/infolab5/ypeilin/Installation/htdocs/VIRLab_UD/addFun.php on line14</li>
                      <li>Change the link of tutorial</li>
                      <li>Let the user to be able to edit in the evaluation section. In your current segment to do experiment I have to go back and forth between pages to do small changes</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>99</td>
                  <td class="success comments">
                    <ul>
                      <li>The IR Virtual Lab system was very intuitive and easy to use. It made the implementation of rather complicated IR retrieval functions much easier than it otherwise would have been</li>
                      <li>I also like that the leaderboard is instantly updated with the results of the evaluations, making it easy to see how well your retrieval functions perform</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>when evaluating a function, the system can sometimes be very slow, especially if several students are working at the same time. While I am aware that to some degree this is unavoidable due to working with large collections of data, anything that can be done to speed up the system would be greatly appreciated</li>
                      <li>Add the ability to see the C code for some of the system’s features (such as for(occur), tf[i] or TFC[i]). This way, if the student needs to implement an idea or variable that is not already defined by the system, they have some guidance of how to go about implementing the new feature on their own</li>
                      <li>Improve the English grammar for some of the status messages</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>100</td>
                  <td class="success comments">
                    <ul>
                      <li>The system is really great</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>The speed of the system</li>
                      <li>Export our data as a table</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>92</td>
                  <td class="success comments">
                    <ul>
                      <li>It helps me a lot to understand different retrieval functions deeply</li>
                      <li>To evaluate the functions by setting different parameters makes the learning and exploring process more interesting and attractive</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>To have an “evaluate all” function</li>
                      <li>It’s hard to get into the system when many users are on it</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>98</td>
                  <td class="success comments">
                    <ul>
                      <li>It helps me a lot to understand different retrieval functions deeply</li>
                      <li>To evaluate the functions by setting different parameters makes the learning and exploring process more interesting and attractive</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>There is currently a problem when using the […] syntax to provide multiple values that if new values are not added to the end of the vector, they value of the variable on the evaluation disappears. i.e. s = [0 1 2] will tell you the s value that corresponds to the evaluation values but changing it to [0 0.5 1 2] will make the s values that correspond go away</li>
                      <li>Using processing power on the requesting computer to make evaluations run more quickly instead of relying entirely on the server.</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>97</td>
                  <td class="success comments">
                    <ul>
                      <li>I want to thank you for giving us an opportunity to learn by providing such a great virtual lab</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>A main concern is speed of evaluations which is somehow slow and I think a perfect solution is to provide the ability of offline evaluation, as you mentioned in class</li>
                      <li>It would be great if the system could show each user a table containing all results s/he obtained so far</li>
                      <li>It can be really useful if system can provide the ability to evaluate the performance of a function (based on values of a tuning variable) for the average of two existing numbers without editing the function’s code</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>104</td>
                  <td class="success comments">
                    <ul>
                      <li>Easy to use interface</li>
                      <li>Small learning curve.  Easy to use without a lot of time spent learning how to use the system</li>
                      <li>Nice data set to start with</li>
                      <li>Large amount of probabilistic terms, i.e. DF[i], qf[i], etc</li>
                      <li>Based on C programming language makes it easy to use, i.e. limited special syntax</li>
                      <li>When the server isn’t bogged down fulfilling other users requests, runs fast</li>
                      <li>The system is a nice way to have the search function come alive to students, i.e. one gets a chance to see search functions to “come alive”</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>Server seems to be slow.  Often I had to wait a few minutes to do things like log in or load a function.  Other times, the website ran quickly.  Could it be that when someone is running an evaluation, the evaluation sucks up too much server computer resources?  I ran an evaluation and then tried to open a new window to the login screen and the login screen would not be displayed until the evaluation completed.  Would it be possible to somehow force the processing of the evaluation to run client side?  So that one student does not slowdown another student</li>
                      <li>Seems that function naming needs to be better defined.  I named my implementation of Okapi as “Okapi” and it gave an error when I tried to load the function after saving.  I changed the name to ssparksOkapi and have not seen the error again.  Not sure if the problem was naming.  Wasn’t clear immediately that the name needs to be universally unique.  Maybe have a feature that will let the user know that his/her name is not unique</li>
                      <li>Tutorial lists the document frequency of term as “df[i]” but the keyword is really “DF[i]”</li>
                      <li>It would be nice if back buttons worked better.  At most screens, a back press gives an error that the form needs to be resubmitted</li>
                      <li>It would be nice if one could change the constants from the evaluation screen. It appears that one must go to manage-edit screen, make the change to the constant and then go back to the evaluation screen to run with the changes</li>
                      <li>Get the occasional mysql too many connections error when navigating to the evaluation screen</li>
                      <li>Error details like that in the screenshot above should not be shown to the end user. Not only are these details only interesting to the developer, but it could give a potential attacker too much information about the implementation of the design and source code</li>
                      <li>It would be nice if there could be a progress indicator.  Although, progress indicators can be difficult to do with websites</li>
                      <li>Once evaluation finishes, the page scrolls up to the top.  It would be nice if the website did not scroll so that user does not need to scroll down to the results</li>
                      <li>Occasionally get an error when trying to run an evaluation.  Latest occurrence was at approximately 23:28 on March 23, 2014.  Sometimes the evaluation run needs to be rerun, sometimes it does not and the result data will be on the evaluation screen.  My instincts are telling me a race condition.  I have no idea why they are telling me race condition but they are</li>
                      <li>It seems a bit strange how the confirm-to-run-the-evaluation-popup shows up at the bottom of the screen.  Center or top of the screen is a more intuitive design.  I use Chrome so this may be due to my browser</li>
                      <li>Probably little or nothing that can be done about this request, but it would be nice if at the design or manage-edit windows, one could press <CTRL>-S and have it save the changes instead of trying to save the html for the webpage.  Is there a way to override these key strokes?</li>
                      <li>It would be nice if the order of the functions in the manage screen would be based on the most recently modified equations.  This would reduce time spent scrolling.  This actually would not matter if the user could edit the functions from the evaluation screen</li>
                      <li>It would save the user time if the “Save” button was also above the code textbox.  Every time the code is edited the user must scroll down to save the changes</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>94</td>
                  <td class="success comments">
                    <ul>
                      <li>With practicing implement different formulas, I had a deeper understanding the different modules, like vector-space model and probabilistic model</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>Evaluation is slow</li>
                      <li>If I want to edit one of the existing functions, I have to rename a new one. Or my code will be wiped off</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>109</td>
                  <td class="success comments">
                    <ul>
                      <li>VIRLab is a very good practice on helping us to concerntrate on designing retrieval functions without waste too much time on implementation untill we can see the result, so it is attractive and can spur our creativity</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>everytime when I assign multiple values to a parameter the evaluation process need us to click the evaluation button on every single parameter and wait for minutes to click the next button, this is very tedious work from the point of me. Sometimes I just open a lot of sessions in a browser to click buttons in somehow a more parallel way (I even write a script to do the task at last). I think it is a better idea to provide the use a list of checkboxes to select the desired parameters the user wants to evaluate and just click a single button to evaluate them all at once</li>
                      <li>the VIRLab front-end will send user's password in plaintext when login, this is not very respectful to users because they may risk exposing their password to others</li>
                      <li>Sometimes the function already saved may not be able to open again(prompting some error in finding files)</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>101</td>
                  <td class="success comments">
                    <ul>
                      <li>VIRLab is a very good practice on helping us to concerntrate on designing retrieval functions without waste too much time on implementation untill we can see the result, so it is attractive and can spur our creativity</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>The system is slow</li>
                      <li>The tutorial should include more commands such as how a square root can be implemented or the power of two can be implemented</li>
                      <li>Sometimes the function already saved may not be able to open again(prompting some error in finding files)</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>108</td>
                  <td class="success comments">
                    <ul>
                      <li>This system is really easy-operating</li>
                      <li>The interface and sections is simple and friendly</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>Build a section “Forgot your password” for your system</li>
                      <li>When doing evaluation for managing function, sometimes some unknown error occurred, which will cause the website breakdown</li>
                    </ul>
                  </td>
                </tr>

                <tr>
                  <td>110</td>
                  <td class="success comments">
                    <ul>
                      <li>The tutorial of VIRLab is helpful</li>
                    </ul>
                  </td>
                  <td class="warning comments">
                    <ul>
                      <li>It is better to show us how to use it in the class</li>
                      <li>A local version of VIRLab is desirable. After we finish the test-version locally, we will upload the code to the server. Because the server is too busy to make the parameter tuning test of retrieval functions</li>
                    </ul>
                  </td>
                </tr>

            </tbody>
        </table>
      </div>
    </div>
   
    <div class="tab-pane fade" id="todolist">
      <div>
        <p>Here is the summary of the comments. The order is based on the importance level from high to low</p>
        <table class="table table-bordered table-hover">
          <thead>
              <tr> 
                  <th>Bugs or Suggestions</th>
                  <th># of metioned</th>
                  <th>Solution (we can discuss about this)</th>
              </tr>
          </thead>

          <tbody>
              <tr>
                <td>The system crashes because of "too many mysql connections" 
                  or "cannot create new thread due to lack of memory"</td>
                <td>5</td>
                <td>
                  <p>
                    Although this problem was only reported 5 times, I still put 
                    it as our most urgent task. The reason is that the error is not 
                    simply related to the error message, but also has something to
                    do with our design.
                  </p>
                  <p>
                    The "too many mysql connections" is because of too many mysql
                    connections at the same time. Why is that? I think there are 
                    two reasons: (1) we have too many databases. (2) lots of the 
                    connections to db are unnecessary or they are not closed after 
                    connection.
                  </p>
                  <p>
                    The "cannot create new thread due to lack of memory" is due to
                    the system level limits for the number of active processes of 
                    the user (Hao in this case). I modify the limit so that it 
                    won't happen again.
                  </p>
                  <p>
                    I think the solution to "too many mysql connections" may need 
                    some works. I list them below:
                    <ol>
                      <li>
                        Modify the DB structure: We only need ONE database in 
                        DB engine for each system. We can have different prefix 
                        for these databases so that they are seperated from each 
                        other. We can take the advantage of relational database 
                        and carefully re-design the tables in the single database.
                        This way, the databases are easier to manage and are 
                        highly portable. Moreover, we can have ONE connection 
                        to the desired database all the time and solve the 
                        problem of "too many mysql connections"
                      </li>
                      <li>
                        Manage the connection to database in ONE place. Currently,
                        Hao and I use different code to manage the usage of database,
                        this may cause problem and is waste of database connections.
                        Instead, we can use persistent connection and manage it in 
                        a single place in the code so that the connection to database
                        is easy to track and manage.
                      </li>
                    </ol>
                  </p>
                </td>
              </tr>

              <tr>
                <td>The system is slow OR the system is blocking when someone(including 
                  user himself) is evaluating functions</td>
                <td>17</td>
                <td>
                  <p>
                    I think the cause of this problem is Hao uses <em>exec (or the like)</em>
                    function to run a system executable which causes the web server 
                    blocking.
                  </p>
                  <p>
                    Solution:
                    <ol>
                      <li>
                        Make the executable run in the background.
                      </li>
                      <li>
                        I found that the executable take one query as arguments.
                        So we can use multiprocessing or multithreading to run 
                        the program which will be much faster.
                      </li>
                      <li>
                        I do not think it is necessary 
                        to write our own code to judge the results. <em>trec_eval</em>
                        is more powerful and more flexible. Generate the results 
                        as files and let <em>trec_eval</em> deal with it.
                      </li>
                      <li>
                        Change the database of evaluations to include only one 
                        column of performance. Put a json string there which includes
                        all desired performace, e.g. MAP, P@10, nDCG@234.
                      </li>
                      <li>
                        Change the workflow of evaluation. Do not block the requests.
                        I am not sure how to do this. But progress bar or dynamic 
                        status text may help.
                      </li>
                    </ol>
                  </p>
                </td>
              </tr>

              <tr>
                <td>Offline evalution OR evaluate locally</td>
                <td>3</td>
                <td>
                  <p>
                    Not sure what exactly they mean. I feel this is related to 
                    the previous problem.
                  </p>
                </td>
              </tr>

              <tr>
                <td>No way to stop user from evaluating functions. Lots of 
                  functions and function groups are mixed together and it is 
                  really hard to grade the assignment</td>
                <td>I(Peilin) find it is important</td>
                <td>
                  <p>
                    Solution:
                    <ol>
                      <li>
                        Use timestamp to control the behavior of students.
                      </li>
                      <li>
                        Split <em>"functions for tuning parameters"</em>
                        and <em>"functions for submission"</em>.
                        The <em>"functions for submission"</em> may include
                        a description field which let students carefully describe
                        the details of the methods. I think this will benefit 
                        both students and the grader. Students can decide which 
                        function to submit and the submission is easy to manage.
                        Grader can only look at the submitted functions and their
                        descriptions which makes the process easier and faster.
                      </li>
                    </ol>
                  </p>
                </td>
              </tr>

              <tr>
                <td>Show the detailed usage of VIRLab on class</td>
                <td>3</td>
                <td>
                  
                </td>
              </tr>

              <tr>
                <td>
                  Tutorial Related
                  <ul>
                    <li>The tutorial should be at somewhere that are easy to find 
                      especially when writing retrieving functions.</li>
                    <li>Tutorial lists the document frequency of term as “df[i]” but the keyword is really “DF[i]”</li>
                    <li>The tutorial should include more commands such as how a square root can be implemented or the power of two can be implemented</li>
                  </ul>
                </td>
                <td>3</td>
                <td>
                  
                </td>
              </tr>

              <tr>
                <td>
                  UI Related
                  <ul>
                    <li>
                      Text box for the compiler is not very wide. It would 
                      work much better if it was based on the screen size
                    </li>
                    <li>
                      It would be nice if back buttons worked better. At most 
                      screens, a back press gives an error that the form needs 
                      to be resubmitted
                    </li>
                    <li>
                      Once evaluation finishes, the page scrolls up to the 
                      top. It would be nice if the website did not scroll so 
                      that user does not need to scroll down to the results
                    </li>
                    <li>
                      It seems a bit strange how the confirm-to-run-the-evaluation-popup 
                      shows up at the bottom of the screen. Center or top of the 
                      screen is a more intuitive design. I use Chrome so this 
                      may be due to my browser
                    </li>
                    <li>
                      Probably little or nothing that can be done about this 
                      request, but it would be nice if at the design or 
                      manage-edit windows, one could press -S and have it save 
                      the changes instead of trying to save the html for the 
                      webpage. Is there a way to override these key strokes?
                    </li>
                    <li>
                      It would be nice if the order of the functions in the 
                      manage screen would be based on the most recently modified 
                      equations. This would reduce time spent scrolling. This 
                      actually would not matter if the user could edit the 
                      functions from the evaluation screen
                    </li>
                    <li>
                      It would save the user time if the “Save” button was also 
                      above the code textbox. Every time the code is edited the 
                      user must scroll down to save the changes
                    </li>
                  </ul>
                </td>
                <td>3</td>
                <td>
                  Lots of work to do ...
                </td>
              </tr>

              <tr>
                <td>
                  "Evaluat ALL" for function groups
                </td>
                <td></td>
                <td>
                  
                </td>
              </tr>

              <tr>
                <td>
                  Change the code of function at evaluation page
                </td>
                <td>2</td>
                <td>
                  
                </td>
              </tr>

              <tr>
                <td>
                  No way to look at the ranking results.
                </td>
                <td></td>
                <td>
                  Like I said before, we should keep the ranking results and let 
                  <em>trec_eval</em> to deal with it. Maybe it is helpful to add 
                  some links which show the basic information of the collection 
                  and the official queries of it. <strong>Another comment:</strong>
                  Is that necessary to stem the queries? At least for Indri it is
                  not necessary. Queries after stemming are hard to understand 
                  and is not user friendly.
                </td>
              </tr>

              <tr>
                <td>
                  Front-End sends password as plain-text
                </td>
                <td></td>
                <td>
                  Encrypt the password before post.
                </td>
              </tr>

              <tr>
                <td>
                  Add “Forgot your password” function for the system
                </td>
                <td></td>
                <td>
                  
                </td>
              </tr>

              <tr>
                <td>
                  Export results as CSV file
                </td>
                <td></td>
                <td>
                  
                </td>
              </tr>

          </tbody>
        </table>
      </div>
    </div>

    </div>



    <div style="margin-bottom:20px;"> </div>

    <script src="static/js/jquery.min.js"></script>
    <script src="static/js/bootstrap.min.js"></script>

  </body>
</html>
