<?php 

namespace Laravel\Dusk {

    class Browser
    {
        use Concerns\InteractsWithAuthentication,
            Concerns\InteractsWithCookies,
            Concerns\InteractsWithElements,
            Concerns\InteractsWithJavascript,
            Concerns\InteractsWithMouse,
            Concerns\MakesAssertions,
            Concerns\WaitsForElements;

        public static $baseUrl;
        public static $storeScreenshotsAt;
        public static $storeConsoleLogAt;
        public static $userResolver;
        public $driver;
        public $resolver;
        public $page;

        /**
         * Browser constructor.
         * @return Browser
         */
        public function __construct($driver, $resolver = null)
        {
        }

        /**
         * @return Browser
         */
        public function visit($url)
        {
        }

        /**
         * @return Browser
         */
        public function visitRoute($route, $parameters = [])
        {
        }

        /**
         * @return Browser
         */
        public function on($page)
        {
        }

        /**
         *
         * @return Browser
         */
        public function refresh()
        {
        }

        /**
         *
         * @return Browser
         */
        public function maximize()
        {
        }

        /**
         * @return Browser
         */
        public function resize($width, $height)
        {
        }

        /**
         * @return Browser
         */
        public function screenshot($name)
        {
        }

        /**
         * @return Browser
         */
        public function storeConsoleLog($name)
        {
        }

        /**
         * @return Browser
         */
        public function with($selector, Closure $callback)
        {
        }

        /**
         *
         * @return Browser
         */
        public function ensurejQueryIsAvailable()
        {
        }

        /**
         * @return Browser
         */
        public function pause($milliseconds)
        {
        }

        /**
         *
         * @return Browser
         */
        public function quit()
        {
        }

        /**
         * @return Browser
         */
        public function tap($callback)
        {
        }

        /**
         *
         * @return Browser
         */
        public function dump()
        {
        }

        /**
         *
         * @return Browser
         */
        public function stop()
        {
        }

        /**
         *
         * @return Browser
         */
        public function login()
        {
        }

        /**
         * @return Browser
         */
        public function loginAs($userId, $guard = null)
        {
        }

        /**
         * @return Browser
         */
        public function logout($guard = null)
        {
        }

        /**
         * @return Browser
         */
        public function assertAuthenticated($guard = null)
        {
        }

        /**
         * @return Browser
         */
        public function assertGuest($guard = null)
        {
        }

        /**
         * @return Browser
         */
        public function assertAuthenticatedAs($user, $guard = null)
        {
        }

        /**
         * @return Browser
         */
        public function cookie($name, $value = null, $expiry = null, array $options = [])
        {
        }

        /**
         * @return Browser
         */
        public function plainCookie($name, $value = null, $expiry = null, array $options = [])
        {
        }

        /**
         * @return Browser
         */
        public function addCookie($name, $value, $expiry = null, array $options = [], $encrypt = true)
        {
        }

        /**
         * @return Browser
         */
        public function deleteCookie($name)
        {
        }

        /**
         * @return Browser
         */
        public function elements($selector)
        {
        }

        /**
         * @return Browser
         */
        public function element($selector)
        {
        }

        /**
         * @return Browser
         */
        public function click($selector)
        {
        }

        /**
         * @return Browser
         */
        public function rightClick($selector)
        {
        }

        /**
         * @return Browser
         */
        public function clickLink($link)
        {
        }

        /**
         * @return Browser
         */
        public function value($selector, $value = null)
        {
        }

        /**
         * @return Browser
         */
        public function text($selector)
        {
        }

        /**
         * @return Browser
         */
        public function attribute($selector, $attribute)
        {
        }

        /**
         * @return Browser
         */
        public function keys($selector, ...$keys)
        {
        }

        /**
         * @return Browser
         */
        public function type($field, $value)
        {
        }

        /**
         * @return Browser
         */
        public function clear($field)
        {
        }

        /**
         * @return Browser
         */
        public function select($field, $value = null)
        {
        }

        /**
         * @return Browser
         */
        public function radio($field, $value)
        {
        }

        /**
         * @return Browser
         */
        public function check($field, $value = null)
        {
        }

        /**
         * @return Browser
         */
        public function uncheck($field, $value = null)
        {
        }

        /**
         * @return Browser
         */
        public function attach($field, $path)
        {
        }

        /**
         * @return Browser
         */
        public function press($button)
        {
        }

        /**
         * @return Browser
         */
        public function pressAndWaitFor($button, $seconds = 5)
        {
        }

        /**
         * @return Browser
         */
        public function drag($from, $to)
        {
        }

        /**
         * @return Browser
         */
        public function dragUp($selector, $offset)
        {
        }

        /**
         * @return Browser
         */
        public function dragDown($selector, $offset)
        {
        }

        /**
         * @return Browser
         */
        public function dragLeft($selector, $offset)
        {
        }

        /**
         * @return Browser
         */
        public function dragRight($selector, $offset)
        {
        }

        /**
         * @return Browser
         */
        public function dragOffset($selector, $x = 0, $y = 0)
        {
        }

        /**
         *
         * @return Browser
         */
        public function acceptDialog()
        {
        }

        /**
         *
         * @return Browser
         */
        public function dismissDialog()
        {
        }

        /**
         * @return Browser
         */
        public function script($scripts)
        {
        }

        /**
         * @return Browser
         */
        public function mouseover($selector)
        {
        }

        /**
         * @return Browser
         */
        public function assertTitle($title)
        {
        }

        /**
         * @return Browser
         */
        public function assertTitleContains($title)
        {
        }

        /**
         * @return Browser
         */
        public function assertPathIs($path)
        {
        }

        /**
         * @return Browser
         */
        public function assertRouteIs($route, $parameters = [])
        {
        }

        /**
         * @return Browser
         */
        public function assertQueryStringHas($name, $value = null)
        {
        }

        /**
         * @return Browser
         */
        public function assertQueryStringMissing($name)
        {
        }

        /**
         * @return Browser
         */
        public function assertHasCookie($name)
        {
        }

        /**
         * @return Browser
         */
        public function assertCookieValue($name, $value, $decrypt = true)
        {
        }

        /**
         * @return Browser
         */
        public function assertPlainCookieValue($name, $value)
        {
        }

        /**
         * @return Browser
         */
        public function assertSee($text)
        {
        }

        /**
         * @return Browser
         */
        public function assertDontSee($text)
        {
        }

        /**
         * @return Browser
         */
        public function assertSeeIn($selector, $text)
        {
        }

        /**
         * @return Browser
         */
        public function assertDontSeeIn($selector, $text)
        {
        }

        /**
         * @return Browser
         */
        public function assertSourceHas($code)
        {
        }

        /**
         * @return Browser
         */
        public function assertSourceMissing($code)
        {
        }

        /**
         * @return Browser
         */
        public function assertSeeLink($link)
        {
        }

        /**
         * @return Browser
         */
        public function assertDontSeeLink($link)
        {
        }

        /**
         * @return Browser
         */
        public function seeLink($link)
        {
        }

        /**
         * @return Browser
         */
        public function assertInputValue($field, $value)
        {
        }

        /**
         * @return Browser
         */
        public function assertInputValueIsNot($field, $value)
        {
        }

        /**
         * @return Browser
         */
        public function inputValue($field)
        {
        }

        /**
         * @return Browser
         */
        public function assertChecked($field, $value = null)
        {
        }

        /**
         * @return Browser
         */
        public function assertNotChecked($field, $value = null)
        {
        }

        /**
         * @return Browser
         */
        public function assertRadioNotSelected($field, $value = null)
        {
        }

        /**
         * @return Browser
         */
        public function assertSelected($field, $value)
        {
        }

        /**
         * @return Browser
         */
        public function assertNotSelected($field, $value)
        {
        }

        /**
         * @return Browser
         */
        public function selected($field, $value)
        {
        }

        /**
         * @return Browser
         */
        public function assertValue($selector, $value)
        {
        }

        /**
         * @return Browser
         */
        public function assertVisible($selector)
        {
        }

        /**
         * @return Browser
         */
        public function assertMissing($selector)
        {
        }

        /**
         * @return Browser
         */
        public function assertDialogOpened($message)
        {
        }

        /**
         * @return Browser
         */
        public function whenAvailable($selector, Closure $callback, $seconds = 5)
        {
        }

        /**
         * @return Browser
         */
        public function waitFor($selector, $seconds = 5)
        {
        }

        /**
         * @return Browser
         */
        public function waitUntilMissing($selector, $seconds = 5)
        {
        }

        /**
         * @return Browser
         */
        public function waitForText($text, $seconds = 5)
        {
        }

        /**
         * @return Browser
         */
        public function waitForLink($link, $seconds = 5)
        {
        }

        /**
         * @return Browser
         */
        public function waitUntil($script, $seconds = 5)
        {
        }

        /**
         * @return Browser
         */
        public function waitUsing($seconds, $interval, Closure $callback, $message = null)
        {
        }
    }
}
