<?php

$lang['register_username ']='Usuario';
$lang['register_password']='Contraseña';
$lang['register_enterprise']='Empresa';
$lang['register_branch']='Sucursal';



$lang['register_username']='Jefe de Empresa';
$lang['register_password']='Contraseña';
$lang['register_go']='Ir';
$lang['register_cancel']='Cancelar';
$lang['register_new']='Nueva Empresa';
$lang['register_enterprise']='Nombre Empresa';
$lang['register_subsidary']='Nombre Sucursal';
$lang['register_user_required']='Usuario es un campo requerido';
$lang['register_pass_required']='Password es un campo requerido';
$lang['register_enterprise_required']='Nombre de la Empresa es un campo requerido';
$lang['register_unsuccessfully']='Ese usuario ya existe';
$lang['register_adviser_termsofuse']='Condicones de uso:';
$lang['register_adviser_agrewithterms']='Acepto los  %s  de uso';
$lang['register_adviser_agrewithterms_this']='términos y condiciones';
$lang['register_adviser_yes']='Si';
$lang['register_adviser_no']='No';
$lang['register_adviser_mustacceptterms']='Debe leer y aceptar las condiciones de uso';
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
