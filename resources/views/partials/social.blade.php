<ul class="list-inline style-icons text-center">
    <li class="{{ isset($facebook) ? $facebook : '' }}"><a href="{{ route('facebook') }}"><i class="fa fa-facebook icon-round icon-round-sm icon-color-fb"></i></a></li>
    <li class="{{ isset($google) ? $google : '' }}"><a href="{{ route('google') }}"><i class="fa fa-google-plus icon-round icon-round-sm icon-color-gg"></i></a></li>
    <li class="{{ isset($about) ? $about : '' }}"><a href="#about" data-toggle="modal"><i class="fa fa-question icon-round icon-round-sm icon-color-grey"></i></a></li>
    <li class="{{ isset($account) ? $account : '' }}"><a href="{{ route('account') }}"><i class="fa fa-user icon-round icon-round-sm icon-color-grey"></i></a></li>
    <li class="{{ isset($home) ? $home : '' }}"><a href="{{ route('blog') }}"><i class="fa fa-home icon-round icon-round-sm icon-color-home"></i></a></li>
</ul>