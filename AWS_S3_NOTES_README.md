# AWS_S3_NOTES_README.md

## Why do we need the cloud for storing images?

Q: Why do we need to use the cloud for storing images, when we used to use a local server folder for storing images? 

A: Need a common storage location that the front-end and back-end can both access. 

## Why S3?

Amazon Web Services is the cloud service I am used to, that I've been following, that I've been keeping up with, that I've been learning. And now there is [Laravel's Vapor](https://vapor.laravel.com), which is completely AWS based. 

You can use whatever cloud service you want, that's the beauty of using Laravel for my LaSalle Software.

These instructions are based on my actual usage and experience with AWS S3.

## CAVEAT!

The instructions I enumerate here are a guide only, provided as a convenience only. 

Your following the instructions I enumerate here does NOT ensure the highest security possible for you, and does NOT ensure that you are using Amazon Web Services in a way that is appropriate for you.

The instructions I enumerate here may be out of date. 

Every individual AWS service costs! Be careful what you click! It is so easy to click something in AWS and then realize later that you selected a separately billed service whilst traversing the AWS console. 

To the best of my knowledge, setting up IAM policies, groups, users, and user credentials is free. 

For reference: [https://dev.to/matttyler/the-hitchhiker-s-guide-to-s3-access-controls-2h0a](https://dev.to/matttyler/the-hitchhiker-s-guide-to-s3-access-controls-2h0a)

## Step 1: Double check that you have these dependencies in your composer.json

Yes, I have [these dependencies](https://laravel.com/docs/master/filesystem#driver-prerequisites) in composer.json already. Yes, I am asking you to double check anyways because if something is not working this is the first thing to check. Might as well take a peek now!

Please ensure that these two lines are in your composer.json's require section:

```
"league/flysystem-aws-s3-v3": "~1.0",
"league/flysystem-cached-adapter": "~1.0",
```

## Step 2: Set the config parameter to "s3"

- go to the "config/lasallesoftware-library.php" config file
- scroll down to the "Filesystem Disk Where Images Are Stored" section
- set the parameter to 's3': ```'lasalle_filesystem_disk_where_images_are_stored'  => 's3',```

## Step 3: Create the bucket where you want to store your images

- log into your AWS console
- click "Services" at the top left
- click "S3" under the "Storage" heading in the rather busy drop-down
- click the "Create bucket" button
- type the name of your new bucket in the "Bucket name" box
- the "Region" box should have a region pre-filled in. This region should be fine. However, if not, click this box for the region list drop-down and click the appropriate region
- ignore the "Copy settings from an existing bucket" box
- click "Next" at the bottom right
- you should now be on the "Properties" form. Generally, just skip this stuff. If you need something set, then go ahead -- please be aware that some of these options may trigger additional AWS usage fees
- click "Next" at the bottom right 
- you should now be on the "Block public access (bucket settings)" form
- you should see one checkbox labelled "Block all public access" checked, with four subsidiary checkbox options underneath unchecked and greyed out
- uncheck the main checkbox labelled "Block all public access"
- the four subsidiary checkboxes should remain unchecked, but are now un-greyed-out
- leave these four subsidiary checkboxes unchecked
- so, you should not see five unchecked boxes -- leave all five unchecked
- ignore "Manage system permissions"; however, if you want this feature, go for it. Please be aware that this option may trigger additional AWS usage fees
- click "Next" at the bottom right
- you should be on the "Review" form, with "Name and region" displaying at the top. 
- everything look ok? Then click "Create bucket" at the bottom right.
- you should see your new bucket in your (ahem) bucket list
- your new bucket should say, under the "Access" column, "Objects can be public".

## Step 4: Assign your new bucket "GetObject" permission

- in your list of buckets, click the new bucket you just created
- click the "Permissions" tab at the top
- click the "Bucket Policy" button on the top
- paste the following into the "Bucket policy editor":

 ```
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "PublicReadGetObject",
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::your-new-bucket's-name/*"
        }
    ]
}
 ``` 

- over-write "your-new-bucket's-name" with the actual name of your new bucket
- click the "Save" button on the top right of the edit box
- you should see the message "This bucket has public access"

## Step 5: Assign your new bucket new CORS permission

- you should be seeing the "CORS configuration" button
- please click this "CORS configuration" button
- paste the following into the "CORS configuration editor":

```
<CORSConfiguration>
  <CORSRule>
    <AllowedOrigin>https://yourdomain.com</AllowedOrigin>
    <AllowedMethod>GET</AllowedMethod>
    <AllowedMethod>POST</AllowedMethod>
    <AllowedMethod>DELETE</AllowedMethod>
    <AllowedHeader>*</AllowedHeader> 
  </CORSRule>
</CORSConfiguration>
```

- over-write "https://yourdomain.com" with the actual name of your app's domain
- click the "Save" button on the top right of the edit box

## Step 6: Enter your new bucket name into your .env's "AWS_BUCKET" parameter

- open a new window with your application's .env file
- paste your new bucket's name to your .env's "AWS_BUCKET" parameter

## Step 7: Enter your .env's "AWS_URL" parameter

The "AWS_URL" environment parameter is in the form: https://your-bucket's-name dot s3 dot region dot amazonaws dot com.

The last part, "amazonaws.com", probably changes depending on what amazon domain of your AWS account.

So, you need your bucket's name; and, you need your region. 

- return to your .env file
- start building your "AWS_URL" environment parameter by enter "https://your-bucket's-name.s3"
- return to your AWS console
- note the "Region" your bucket resides. 
- go to  [AWS Availabile Regions](https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/using-regions-availability-zones.html#concepts-available-regions)
- look for your region in the "Name" column
- paste the "Code" of your region into the "AWS_URL": "https://your-bucket's-name.s3.the-code-you-just-looked-up"
- append "AWS_URL" with ".amazonaws.com" (or whatever your particular amazonaws domain is)

So, your "AWS_URL" environment parameter will look like: https://your-bucket's-name.s3.the-code-you-just-looked-up.amazonaws.com

UPDATE! Ah, on a fresh deploy, the AWS_URL is of the form: "https://s3.region.amazonaws.com/bucket". So you'll have to double-check!

## Step 8: Create a custom "policy" for your app's S3 access

- click "Services" at the top left
- click "IAM" under the "Security, Identity, & Compliance" heading in the rather busy drop-down
- click "Policies" in the left vertical menu, under the "Access management" heading
- click the "Create policy" button at the top 
- click the "Visual editor" tab
- click the "Choose a service" link next to "Service"
- in the "Find a service" search box, type "s3"
- a "S3" link should appear below -- please click this link
- click the arrow to the immediate left of the word "Read" under the "Access level" heading
- a bunch of options display under "Read". The first option is "DescribeJob". Ok!
- find "GetObject" and click its checkbox
- click the arrow to the immediate left of the word "Write" 
- find "PutObject" and click its checkbox
- find "DeleteObject" and click its checkbox
- scroll down to the "Resources". It should say here to "Specify object resource ARN for the PutObject and 2 more actions"
- click the phrase "Specify object resource ARN for the PutObject and 2 more actions"
- you should see the phrase "Add ARN to restrict access"
- click the "Add ARN" link
- you should see the "Add ARN(s)" pop-up box
- enter the name of the bucket you created in step #2 in the "Bucket name *" box
- click the "Object name *" "Any" checkmark
- please double check that you did NOT, repeat NOT, check the bucket name's "Any" checkbox. 
  ==> WARNING! Clicking the bucket name's "Any" checkbox COULD BE VERY VERY BAD.
- are we good? Ok! Now please click the "Add" button at the bottom right of the pop-up box
- you should be back in the policy visual editor
- note that now the "Resources" "object" box specifies the AWS resource that you just specified in the pop-up box
- click "Review policy" button at the bottom right
- you should be in the "Create policy" form
- enter a name in the "Name" field (I named mine "LaSalle_Software_S3_my-bucket's-name_Bucket_Policy")  
- I recommend that you enter a description, because it's a help to have a prompt of what this thing is that you are doing, and it also helps you recollect that you created this thing! (I entered in mine "Permission to GetObject, PutObject, and DeleteObject for my my-bucket's-name Bucket")
- are we good? Ok! Then click the "Create policy" button at the bottom right
- you should be back at the policies listing, with a message at the top confirming that your policy was created
- you should look up the policy you just created --> see what it looks like!

## Step 9: Create a new group in IAM

- click "Groups" in the left vertical menu, under the "Access management" heading
- click the "Create New Group" button at the top
- enter the new group's name in the "Group Name" box (I entered "LaSalleSoftware")
- click the "Next Step" button at the bottom right
- you should be in the "Attach Policy" page
- in the search box, start typing in the name of the policy you just created
- your custom policy should be displayed
- click the checkmark beside your new custom policy
- click the "Next Step" button at the bottom right
- you should be in the "Review" page
- are the review details basically indecipherable? Yeah, well, after the hundreth time traversing the AWS console, and reading a few articles, it slowly starts to mostly make sense
- are we good? Ok! Then click the "Create Group" button at the bottom right

## Step 10: Create a new IAM "API" user

- click "Users" in the left vertical menu, under the "Access management" heading
- click the "Add user" button at the top
- click your new user's name in the "User name*" box
- click the "Programmatic access" check box in the "Access type*" section
- absolutely do NOT, repeat NOT, click the "AWS Management Console access" check box
  ** my attitude is: a user is either "Programmatic access" or "AWS Management Console access", never both
- click the "Next: Permissions" button at the bottom right  
- you should be in the "Set permissions" page
- your new group should be listed. 
- click the checkmark next to the new group you just created
- click the "Next: Tags" button at the bottom right
- you should be in the "Add tags (optional)" page. I generally ignore this page
- click the "Next: Review" button on the bottom right
- you should be on the "Review" page
- your new user's "AWS access type" should say "Programmatic access - with an access key"
- we're good? Ok! Click the "Create user" button on the bottom right
- you should see a "Success" message
- you should see the "Show" link 
- open a new window and open your application's .env file, if you have yet to do so
- paste the access key ID to your .env's "AWS_ACCESS_KEY_ID" parameter
- click the "Show" link
- paste the secret access key to your .env's "AWS_SECRET_ACCESS_KEY" parameter
- when you leave this page, the secret access key is not accessible, so be careful with this copy-paste
- click the "Close" button at the bottom right

## Step 11: Optional: use S3 folders

Do you want to use S3 folders with your S3 buckets? Then follow these steps:

## Step 11(a): Specify the folders you want to use for each set of featured images

- go to your ```config/lasallesoftware-library.php``` config file
- scroll down to the "PATHS FOR FEATURED IMAGES" section
- specify the folders you want to use for each "resource". There are in-line directions

## Step 11(b): Create the folders in S3

- log into your AWS console, if necessary
- click "Services" at the top left
- click "S3" under the "Storage" heading in the rather busy drop-down
- you should see a list of your buckets
- click the bucket that you previously created
- click the "Create folder" button
- enter the name of your new folder for each "Nova resource" 

***********************************
** end of AWS_S3_NOTES_README.md **
***********************************

