<div class="row text-center">
    <form method="post" action="{{ @BASE }}/login">
        <div class="form-group">
        <label for="data[pass_word]">Password</label>            
        <input type="password" name="data[pass_word]" />
        <input type="submit" name="submit" value="Login" />
        </div>
    </form>
</div>