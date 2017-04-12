<?php
$lang['register_enterprise']='Enterprise';
$lang['register_branch']='Branch';

$lang['register_email']='Email';
$lang['register_email2']='Repeat Email';

$lang['register_username']='Enterprise Owner';
$lang['register_password']='Password';

$lang['register_repeat_password']='Repeat password';
$lang['register_go']='Go';
$lang['register_cancel']='Cancel';
$lang['register_new']='New Enterprise';
$lang['register_enterprise']='Name of Enterprise';
$lang['register_subsidary']='Name of Branch';
$lang['register_user_required']='The Username is a required field.';
$lang['register_pass_required']='The Password is a required field';
$lang['register_enterprise_required']='Enterprise is a required field';
$lang['register_unsuccessfully']='This user already exist';
$lang['register_adviser_new']='New Publicist';
$lang['register_adviser_termsofuse']='Terms of use:';
$lang['register_adviser_agrewithterms']='I agree with the %s';
$lang['register_adviser_agrewithterms_this']='terms and condictions';
$lang['register_adviser_yes']='Yes';
$lang['register_adviser_no']='No';
$lang['register_adviser_mustacceptterms']='You must read and accept the temrs';
$lang['register_phone_required']='The Phone is a required field and must be a number';
$lang['register_zip_required']='The Zip is a required field and must be a number';
$lang['register_adviser_terms']='The next version of cron, with the release of Unix System V, was created to extend the capabilities of cron to all users of a Unix system, not just the superuser. Though this may seem trivial today with most Unix and Unix-like systems having powerful processors and small numbers of users, at the time it required a new approach on a 1 MIPS system having roughly 100 user accounts.
In the August, 1977 issue of the Communications of the ACM, W. R. Franta and Kurt Maly published an article entitled "An efficient data structure for the simulation event set" describing an event queue data structure for discrete event-driven simulation systems that demonstrated "performance superior to that of commonly used simple linked list algorithms," good behavior given non-uniform time distributions, and worst case complexity , "n" being the number of events in the queue.
A graduate student, Robert Brown, reviewing this article, recognized the parallel between cron and discrete event simulators, and created an implementation of the Franta-Maly event list manager (ELM) for experimentation. Discrete event simulators run in "virtual time", peeling events off the event queue as quickly as possible and advancing their notion of "now" to the scheduled time of the next event. By running the event simulator in "real time" instead of virtual time, a version of cron was created that spent most of its time sleeping, waiting for the moment in time when the task at the head of the event list was to be executed.
The following school year brought new students into the graduate program, including Keith Williamson, who joined the systems staff in the Computer Science department. As a "warm up task" Brown asked him to flesh out the prototype cron into a production service, and this multi-user cron went into use at Purdue in late 1979. This version of cron wholly replaced the /etc/cron that was in use on the Computer Science department\'s VAX 11/780 running 32/V.
The algorithm used by this cron is as follows:
On start-up, look for a file named .crontab in the home directories of all account holders.
For each crontab file found, determine the next time in the future that each command is to be run.
Place those commands on the Franta-Maly event list with their corresponding time and their "five field" time specifier.
Enter main loop:
Examine the task entry at the head of the queue, compute how far in the future it is to be run.
Sleep for that period of time.
On awakening and after verifying the correct time, execute the task at the head of the queue (in background) with the privileges of the user who created it.';
?>