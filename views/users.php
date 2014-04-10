
<section>
<h2>Gestion des utilisateurs</h2>

Liste des utilisateurs :
<ul>
<?php foreach ($users as $user ) { ?>
	<li><?php echo $user->username ?> [<?php echo $user->role ?>]</li>
<?php } ?>
</ul>

</section>