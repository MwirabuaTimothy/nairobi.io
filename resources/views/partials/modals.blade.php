
    @if(!Sentry::check()) 

    <!-- signin modal-->
    <div class="modal fade sign-in" role="dialog" aria-labelledby="signInLabel" id="signin" aria-hidden="true">
        <div class="modal-backdrop fade in"></div>
        <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Welcome!</h4>
          </div>
          <div class="modal-body">

            <div class="oauth signin">
              <a href="/account/facebook" class="facebook col-xs-6">
                <i class="icon-facebook"></i>
                <span>Sign in<span class="mobile"> with Facebook</span></span>
              </a>
              <a href="/account/google" class="google col-xs-6"><i
                 class="icon-gplus"></i>
                <span>Sign in<span class="mobile"> with Google</span></span>
              </a>
            </div>

            <div class="cell neutral email-signin">

              <h4>Sign in with Email:
                <span class="pull-right" >
                  <a href="#signup" data-toggle="modal">Register>></a>
                </span>
              </h4>
              <!-- <span class="or module">or</span> -->
              <form accept-charset="UTF-8" action="/account/signin" class="new_user" id="new-session" method="post">
                <input name="utf8" type="hidden" value="✓">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="status" tabindex="2" value="signin" class="_hide">

                <input class="form-control" autocapitalize="off" autocorrect="off" autofocus="on" id="email" name="email" placeholder="Email" size="30" type="text">
                <input class="form-control" id="password" name="password" placeholder="Password" size="30" type="password">

                <label class="remember-me">
                  <input name="remember-me" type="hidden" value="0"><input checked="checked" id="remember-me" name="remember-me" type="checkbox" value="1">
                  <label for="remember-me">Remember me</label>
                  <a href="{{ route('forgot-password') }}" data-toggle="modal">Forgot your password?</a>
                </label>

                <input class="button turquoise pull-right" name="commit" type="submit" value="Login">
              </form>

            </div>

          </div>

        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- signup modal-->
    <div class="modal fade sign-up" role="dialog" aria-labelledby="signUpLabel" id="signup" aria-hidden="true">
        <div class="modal-backdrop fade in"></div>
        <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Karibu!</h4>
          </div>
          <div class="modal-body">

            <div class="oauth signup">
              <a href="/account/facebook" class="facebook col-xs-6">
                <i class="icon-facebook"></i>
                <span>Sign up<span class="mobile"> with Facebook</span></span>
              </a>
              <a href="/account/google" class="google col-xs-6">
                <i class="icon-gplus"></i>
                <span>Sign up<span class="mobile"> with Google</span></span>
              </a>
            </div>

            <div class="cell neutral email-signup">

              <h4>Sign up with Email:
                <span class="pull-right" >
                  <a href="#signin" data-toggle="modal">Sign In>></a>
                </span>
              </h4>
              <!-- <span class="or module">or</span> -->
              <form accept-charset="UTF-8" action="/account/signup" class="new_user" id="new-session" method="post">
                <input name="utf8" type="hidden" value="✓">

                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="status" tabindex="2" value="signup" class="_hide">

                <input class="form-control" autofocus="on" autocapitalize="off" autocorrect="off" id="first_name" name="first_name" placeholder="First Name" size="30" type="text">
                <input class="form-control" autofocus="on" autocapitalize="off" autocorrect="off" id="last_name" name="last_name" placeholder="Last Name" size="30" type="text">
                <input class="form-control" autocapitalize="off" autocorrect="off" id="username" name="username" placeholder="user_nam3" size="30" type="text">
                <input class="form-control" autocapitalize="off" autocorrect="off" id="email" name="email" placeholder="Email" size="30" type="text">
                <input class="form-control" id="password" name="password" placeholder="Password" size="30" type="password">
                <input class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" size="30" type="password">

                <label class="checkbox">
                  <input type="checkbox" name="terms"> 
                  I agree to the <a target="_blank" href="javascript:;">Terms and Conditions</a>
                </label>

                <input class="btn green pull-right" name="commit" type="submit" value="Sign Up">
                
              </form>

            </div>
          </div>
        </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
    @endif