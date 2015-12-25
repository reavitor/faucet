<div class="well">
    <check if="{{ @lost_pass }}">
        <false>
    <p>Install Your faucet.</p>
    <p>Make sure your database settings are correct.</p>
    <ol>
        <li>DB_NAME : {{ @DB_NAME }}</li>
        <li>DB_PREFIX : {{ @DB_PREFIX }}</li> 
    </ol>
        </false>
        <true>
        <p>Reset your password.</p>
        </true>
    </check>
    
    <form action="{{ @BASE }}{{ @lost_pass ? '/install/lost_pass' : '/install' }}" method="post">
        <label for="install[db_pass">DB Password</label>
        <input type="password" name="install[db_pass]" />
        <input class="btn btn-primary" type="submit" name="submit" value="Install It" />
    </form>
</div>

