<!-- Vmensik za dodajanje, forma pošilja podatke kot zahteva za akcijo shrani na kontrolerju oglasi-->
<p>Add user</p>

<form action="?controller=account&action=add_account" method="post">
    <div class="form-group">
        <label for="firstname">firstname:</label><input
                type="text" class="form-control" name="firstname"/>
        <label for="lastname">lastname:</label><input type="text" class="form-control" name="lastname"/> <label
                for="username">username:</label><input type="text" class="form-control" name="username"/>
        <label for="email">email:</label><input type="text" class="form-control" name="email"/>
        <label for="password">password:</label><input type="text" class="form-control" name="password"/> <label
                for="naslov">naslov:</label><input type="text" class="form-control" name="naslov"/> <label for="posta">posta:</label><input
                type="text" class="form-control" name="posta"/> <label
                for="telefonska_stevilka">telefonska_stevilka:</label><input type="text" class="form-control"
                                                                           name="telefonska_stevilka"/> <label
                for="starost">starost:</label><input type="text" class="form-control" name="starost"/> <label
                for="spol">spol:</label><input type="text" class="form-control" name="spol"/> <label for="is_admin">is_admin:</label><input
                type="text" class="form-control" name="is_admin"/>

        <input class="btn btn-primary" type="submit" name="Add" value="Add"/>
    </div>
</form>