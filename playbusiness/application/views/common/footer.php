                <script type="text/javascript" defer>
            // Check for browser support of event handling capability
            if (window.addEventListener)
                window.addEventListener("DOMContentLoaded", downloadJSAtOnload, false);
            else if (window.attachEvent)
                window.attachEvent("onload", downloadJSAtOnload);
            else
                window.onload = downloadJSAtOnload;

            // Add a script element as a child of the head
            function downloadJSAtOnload() {
                var element = document.createElement("script");
                element.src = "/js/busi_js.js";
                document.getElementsByTagName('head')[0].appendChild(element);
            }
        </script>
</body>
</html>