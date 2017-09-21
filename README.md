# Minimal MVC Framework
This is a very minimal (buggy) MVC framework built by Daniel Ip (ip4368).

This is built because Daniel was taking a database subject (INFO20003 Database System) in the University of Melbourne in 2016, which required students to write a very simple web app in PHP, without using any frameworks (neither frontend js framework nor backend php framework). The curious student was very confused and did not understand why framework is not allowed (the answer from lecturer was he wanted to see student rendering the html with PHP). I like things to be done in a managable way, it needs to be quick to build, but still managable.

I spent a few days to hack up a very minimal MVC framework, which is not throughly tested and not production ready. It is 100% assignment purpose, which contains 4 php pages (if build separately without using a frameworks.)

This is potentially very buggy and not secure, but this does provides a bit of protection such as SQL injection by providing a escape string helper function (which essentially is just prinft-like interface to mysqli real_escape_string). This frameworks doesn't provide any feature on XSS protection.

This is not expected to be in production ready in any sense and is purely only for education purpose.

If any one is interested on the (badly done) implementation, you are welcome to contact me.
