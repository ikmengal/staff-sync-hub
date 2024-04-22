<footer class="content-footer footer bg-footer-theme">
    <div class="container-fluid">
        <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
            <div>
                ©
                <script>
                    document.write(new Date().getFullYear());
                </script>
                , made by

                <a href="@if(!empty(settings()->website_url)) {{ settings()->website_url }} @else {{ '#' }} @endif" target="_blank" class="fw-semibold">{{ appName() }}</a>
            </div>
            <div>
                @if(settings()->facebook_link)
                    <a href="{{ settings()->facebook_link }}" class="footer-link me-4" target="_blank">Facebook</a>
                @endif
                @if(settings()->instagram_link)
                    <a href="{{ settings()->instagram_link }}" target="_blank" class="footer-link me-4">Instagram</a>
                @endif

                @if(settings()->twitter_link)
                    <a href="{{ settings()->twitter_link }}" target="_blank" class="footer-link me-4" >Twitter</a>
                @endif
                @if(settings()->linked_in_link)
                    <a href="{{ settings()->linked_in_link }}" target="_blank" class="footer-link d-none d-sm-inline-block">Linked In</a>
                @endif
            </div>
        </div>
    </div>
</footer>
