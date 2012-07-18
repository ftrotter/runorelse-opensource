runorelse-opensource
====================

The Open Source version of RunOrElse.com

What its for
====================
This shows how to use your own paypal API credentials and the PayPall Mass Pay API to automatically send a donation to wikimedia foundation if you fail to go a certain number of steps.

It is essentiall behavioural econ for programmers.

The current model is focused on using the FitBit API, but any API could be used in this fashion.
That means that if you can quantify a given behavior you can incentivize it. 

Setup
=======
Copy the config.php.template to one directory higher and edit it.
You will need to visit PayPal to get your API credentials, and apparently, also to apply for Mass Pay access on your account.
You will need to create and app on dev.fitbit.com (remember not to actually publish your app, since its just for you).
dev.fitbit.com will care where what URL is the callback, and you want to point it to the fitbit_callback.php file (whereever you have put it). That file will spit out some OAuth credentials and that is the last thing to copy to the ../config.php file...

It may take some time to get your approval and get it setup, but it should all work.

This is alpha code that screws with your money. Be careful.

Of course, this is all done to promote our commercial service RunOrElse.com, which uses RunKeeper data to automatically set this up. Stil this version gives you the same privilege and will allow to incentivize anything, if you can code in php and you can measure that change in some fashion. 
