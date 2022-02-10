<x-guest-layout>

<!-- Page Container -->
<div id="page-container" class="flex flex-col w-full min-h-screen mx-auto bg-gray-100">
    <!-- Page Content -->
    <main id="page-content" class="flex flex-col flex-auto max-w-full">
      <!-- Hero -->
      <div class="overflow-hidden bg-white">
        <!-- Header -->
        <header id="page-header" class="flex items-center flex-none py-10 bg-white">
          <div class="container flex flex-col px-4 mx-auto space-y-6 text-center md:flex-row md:items-center md:justify-between md:space-y-0 xl:max-w-7xl lg:px-8">
            <div>
              <a href="javascript:void(0)" class="inline-flex items-center space-x-2 text-lg font-bold tracking-wide text-indigo-600 hover:text-indigo-400">
                <svg stroke="currentColor" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="inline-block w-6 h-6 opacity-75 hi-outline hi-cube-transparent"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                <span>Company</span>
              </a>
            </div>
            <div class="flex flex-col space-y-6 text-center md:flex-row md:items-center md:justify-between md:space-y-0 md:space-x-10">
              <nav class="space-x-4 md:space-x-6">
                <a href="javascript:void(0)" class="font-semibold text-gray-900 hover:text-gray-500">
                  <span>Features</span>
                </a>
                <a href="javascript:void(0)" class="font-semibold text-gray-900 hover:text-gray-500">
                  <span>Plans</span>
                </a>
                <a href="javascript:void(0)" class="font-semibold text-gray-900 hover:text-gray-500">
                  <span>About</span>
                </a>
              </nav>
              <div class="flex items-center justify-center space-x-2">
                <a href="/login" class="inline-flex items-center justify-center px-3 py-2 space-x-2 font-semibold leading-6 text-gray-800 bg-white border border-gray-300 rounded shadow-sm focus:outline-none hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                  <span>Login</span>
                  <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 opacity-50 hi-solid hi-arrow-right"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </a>
              </div>
            </div>
          </div>
        </header>
        <!-- END Header -->

        <!-- Hero Content -->
        <div class="container flex flex-col px-4 py-16 mx-auto space-y-16 text-center lg:flex-row-reverse lg:space-y-0 lg:text-left xl:max-w-7xl lg:px-8 lg:py-32">
          <div class="lg:w-1/2 lg:flex lg:items-center">
            <div>
              <div class="inline-flex px-2 py-1 mb-2 text-sm font-semibold leading-4 rounded text-emerald-700 bg-emerald-200">
                New dashboard is live!
              </div>
              <h2 class="mb-4 text-3xl font-extrabold md:text-4xl">
                Premium leads for all your SaaS projects
              </h2>
              <h3 class="text-lg font-medium text-gray-600 md:text-xl md:leading-relaxed">
                Focus on building your amazing service and we will do the rest. We grew more than 10,000 online businesses.
              </h3>
              <div class="flex flex-col justify-center pt-10 pb-16 space-y-2 sm:flex-row sm:items-center lg:justify-start sm:space-y-0 sm:space-x-2">
                <a href="javascript:void(0)" class="inline-flex items-center justify-center px-6 py-4 space-x-2 font-semibold leading-6 text-white bg-indigo-700 border border-indigo-700 rounded focus:outline-none hover:text-white hover:bg-indigo-800 hover:border-indigo-800 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 active:bg-indigo-700 active:border-indigo-700">
                  <span>Pesan Sekarang</span>
                  <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 opacity-50 hi-solid hi-arrow-right"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </a>
                {{-- <a href="javascript:void(0)" class="inline-flex items-center justify-center px-6 py-4 space-x-2 font-semibold leading-6 text-gray-700 bg-gray-200 border border-gray-200 rounded focus:outline-none hover:text-gray-700 hover:bg-gray-300 hover:border-gray-300 focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-gray-200 active:border-gray-200">
                  <span>Start a 30-day trial</span>
                </a> --}}
              </div>
            </div>
          </div>
          <div class="lg:w-1/2 lg:mr-16 lg:flex lg:justify-center lg:items-center">
            <div class="relative lg:w-96">
              <div class="absolute top-0 left-0 w-32 h-48 text-indigo-100 transform -translate-x-16 -translate-y-12 pattern-dots-xl md:h-96 -rotate-3"></div>
              <div class="absolute bottom-0 right-0 w-32 h-48 text-indigo-100 transform translate-x-16 translate-y-12 pattern-dots-xl md:h-96 rotate-3"></div>
              <div class="absolute top-0 right-0 w-32 h-32 -mt-12 -mr-12 bg-yellow-200 bg-opacity-50 rounded-full"></div>
              <div class="absolute bottom-0 left-0 w-32 h-32 -mb-10 -ml-10 transform bg-blue-200 bg-opacity-50 rounded-xl rotate-3"></div>
              <img src="https://source.unsplash.com/MChSQHxGZrQ/800x1000" alt="Hero Image" class="relative mx-auto rounded-lg shadow-lg" />
            </div>
          </div>
        </div>
        <!-- END Hero Content -->
      </div>
      <!-- END Hero -->

      <!-- Features Section: With Images -->
      <div class="bg-gray-100">
        <div class="container px-4 py-16 mx-auto space-y-16 xl:max-w-7xl lg:px-8 lg:py-32">
          <!-- Heading -->
          <div class="text-center">
            <div class="mb-1 text-sm font-bold tracking-wider text-indigo-700 uppercase">
              <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="inline-block w-10 h-10 hi-solid hi-terminal"><path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
            </div>
            <h2 class="mb-4 text-3xl font-extrabold md:text-4xl">
              Fully Featured
            </h2>
            <h3 class="mx-auto text-lg font-medium text-gray-600 md:text-xl md:leading-relaxed lg:w-2/3">
              Amazing and latest features to help you build your next idea with cool tools and super modern technology.
            </h3>
          </div>
          <!-- END Heading -->

          <!-- Features -->
          <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-8">
            <div class="py-5">
              <div class="p-2 mb-8 transition bg-white rounded-lg hover:bg-indigo-500">
                <img src="https://source.unsplash.com/clN6N30q3sw/800x650" alt="Preview Feature Image" class="rounded-lg" />
              </div>
              <h4 class="mb-2 text-lg font-bold">
                Amazing Feature
              </h4>
              <p class="mb-3 leading-relaxed text-gray-600">
                Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti.
              </p>
            </div>
            <div class="py-5">
              <div class="p-2 mb-8 transition bg-white rounded-lg hover:bg-indigo-500">
                <img src="https://source.unsplash.com/zNRITe8NPqY/800x650" alt="Preview Feature Image" class="rounded-lg" />
              </div>
              <h4 class="mb-2 text-lg font-bold">
                Inspiring Feature
              </h4>
              <p class="mb-3 leading-relaxed text-gray-600">
                Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti.
              </p>
            </div>
            <div class="py-5">
              <div class="p-2 mb-8 transition bg-white rounded-lg hover:bg-indigo-500">
                <img src="https://source.unsplash.com/H0r6LB_9rz4/800x650" alt="Preview Feature Image" class="rounded-lg" />
              </div>
              <h4 class="mb-2 text-lg font-bold">
                Cool Feature
              </h4>
              <p class="mb-3 leading-relaxed text-gray-600">
                Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti.
              </p>
            </div>
          </div>
          <!-- END Features -->
        </div>
      </div>
      <!-- END Features Section: With Images -->

      <!-- FAQ Section: To the Side -->
      <div class="bg-white">
        <div class="container px-4 py-16 mx-auto space-y-16 lg:flex lg:justify-between lg:space-x-8 lg:space-y-0 xl:max-w-7xl lg:px-8 lg:py-32">
          <!-- Heading -->
          <div class="text-center lg:text-left">
            <div class="mb-1 text-sm font-bold tracking-wider text-indigo-700 uppercase">
              We Answer
            </div>
            <h2 class="mb-4 text-3xl font-extrabold md:text-4xl">
              Frequently Asked Questions
            </h2>
            <h3 class="text-lg font-medium text-gray-600 md:text-xl md:leading-relaxed">
              Be sure to <a href="javascript:void(0)" class="text-indigo-600 hover:text-indigo-400">get in touch</a> and let us know if you have any further questions.
            </h3>
          </div>
          <!-- END Heading -->

          <!-- FAQ -->
          <div class="space-y-8 lg:w-3/5 lg:flex-none">
            <div class="prose prose-indigo">
              <h4>
                What features are included?
              </h4>
              <p>
                Etiam egestas fringilla enim, id convallis lectus laoreet at. Fusce purus nisi, gravida sed consectetur ut, interdum quis nisi. Quisque egestas nisl id lectus facilisis scelerisque.
              </p>
            </div>
            <div class="prose prose-indigo">
              <h4>
                Do I get access to the community?
              </h4>
              <p>
                Etiam egestas fringilla enim, id convallis lectus laoreet at. Fusce purus nisi, gravida sed consectetur ut, interdum quis nisi. Quisque egestas nisl id lectus facilisis scelerisque.
              </p>
            </div>
            <div class="prose prose-indigo">
              <h4>
                Do you offer email support?
              </h4>
              <p>
                Etiam egestas fringilla enim, id convallis lectus laoreet at. Fusce purus nisi, gravida sed consectetur ut, interdum quis nisi. Quisque egestas nisl id lectus facilisis scelerisque.
              </p>
            </div>
            <div class="prose prose-indigo">
              <h4>
                Are the updates free for life?
              </h4>
              <p>
                Etiam egestas fringilla enim, id convallis lectus laoreet at. Fusce purus nisi, gravida sed consectetur ut, interdum quis nisi. Quisque egestas nisl id lectus facilisis scelerisque.
              </p>
            </div>
          </div>
          <!-- END FAQ -->
        </div>
      </div>
      <!-- END FAQ Section: To the Side -->

      <!-- CTA Section: Simple Boxed -->
      <div class="overflow-hidden bg-gray-100">
        <div class="container px-4 py-16 mx-auto xl:max-w-7xl lg:px-8 lg:py-32">
          <div class="relative">
            <div class="absolute top-0 right-0 w-32 h-32 text-gray-300 transform translate-x-12 -translate-y-16 pattern-dots-lg lg:w-48 lg:h-48"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 text-gray-300 transform -translate-x-12 translate-y-16 pattern-dots-lg lg:w-48 lg:h-48"></div>
            <div class="relative p-10 text-center bg-white rounded shadow lg:py-12 lg:px-16">
              <div class="space-y-10">
                <!-- Heading -->
                <div class="text-center">
                  <h2 class="mb-4 text-3xl font-extrabold md:text-4xl">
                    Ready? <span class="text-indigo-600">Let’s do it!</span>
                  </h2>
                  <h3 class="text-lg font-medium text-gray-600 md:text-xl md:leading-relaxed">
                    Get your own custom dashboard and start building amazing services, always with the most solid and rock steady foundation.
                  </h3>
                </div>
                <!-- END Heading -->

                <!-- CTA -->
                <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-center sm:space-y-0 sm:space-x-2">
                  <a href="javascript:void(0)" class="inline-flex items-center justify-center px-6 py-4 space-x-2 font-semibold leading-6 text-white bg-indigo-700 border border-indigo-700 rounded focus:outline-none hover:text-white hover:bg-indigo-800 hover:border-indigo-800 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 active:bg-indigo-700 active:border-indigo-700">
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 opacity-50 hi-solid hi-arrow-right"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span>Get Started</span>
                  </a>
                  <a href="javascript:void(0)" class="inline-flex items-center justify-center px-6 py-4 space-x-2 font-semibold leading-6 text-gray-700 bg-gray-200 border border-gray-200 rounded focus:outline-none hover:text-gray-700 hover:bg-gray-300 hover:border-gray-300 focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-gray-200 active:border-gray-200">
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 opacity-50 hi-solid hi-information-circle"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span>Learn More</span>
                  </a>
                </div>
                <!-- END CTA -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END CTA Section: Simple Boxed -->

      <!-- Footer: Simple With Social -->
      <footer id="page-footer" class="bg-white">
        <div class="container flex flex-col px-4 py-16 mx-auto space-y-6 text-sm text-center md:flex-row-reverse md:justify-between md:space-y-0 md:text-left lg:text-base xl:max-w-7xl lg:px-8 lg:py-32">
          <nav class="space-x-4">
            <a href="javascript:void(0)" class="text-gray-400 hover:text-indigo-600">
              <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 icon-twitter"><path d="M24 4.557a9.83 9.83 0 01-2.828.775 4.932 4.932 0 002.165-2.724 9.864 9.864 0 01-3.127 1.195 4.916 4.916 0 00-3.594-1.555c-3.179 0-5.515 2.966-4.797 6.045A13.978 13.978 0 011.671 3.149a4.93 4.93 0 001.523 6.574 4.903 4.903 0 01-2.229-.616c-.054 2.281 1.581 4.415 3.949 4.89a4.935 4.935 0 01-2.224.084 4.928 4.928 0 004.6 3.419A9.9 9.9 0 010 19.54a13.94 13.94 0 007.548 2.212c9.142 0 14.307-7.721 13.995-14.646A10.025 10.025 0 0024 4.557z"></path></svg>
            </a>
            <a href="javascript:void(0)" class="text-gray-400 hover:text-indigo-600">
              <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 icon-facebook"><path d="M9 8H6v4h3v12h5V12h3.642L18 8h-4V6.333C14 5.378 14.192 5 15.115 5H18V0h-3.808C10.596 0 9 1.583 9 4.615V8z"></path></svg>
            </a>
            <a href="javascript:void(0)" class="text-gray-400 hover:text-indigo-600">
              <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 icon-instagram"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"></path></svg>
            </a>
            <a href="javascript:void(0)" class="text-gray-400 hover:text-indigo-600">
              <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 icon-github"><path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"></path></svg>
            </a>
            <a href="javascript:void(0)" class="text-gray-400 hover:text-indigo-600">
              <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 icon-github"><path d="M12 0C5.372 0 0 5.373 0 12s5.372 12 12 12 12-5.373 12-12S18.628 0 12 0zm9.885 11.441c-2.575-.422-4.943-.445-7.103-.073a42.153 42.153 0 00-.767-1.68c2.31-1 4.165-2.358 5.548-4.082a9.863 9.863 0 012.322 5.835zm-3.842-7.282c-1.205 1.554-2.868 2.783-4.986 3.68a46.287 46.287 0 00-3.488-5.438A9.894 9.894 0 0112 2.087c2.275 0 4.368.779 6.043 2.072zM7.527 3.166a44.59 44.59 0 013.537 5.381c-2.43.715-5.331 1.082-8.684 1.105a9.931 9.931 0 015.147-6.486zM2.087 12l.013-.256c3.849-.005 7.169-.448 9.95-1.322.233.475.456.952.67 1.432-3.38 1.057-6.165 3.222-8.337 6.48A9.865 9.865 0 012.087 12zm3.829 7.81c1.969-3.088 4.482-5.098 7.598-6.027a39.137 39.137 0 012.043 7.46c-3.349 1.291-6.953.666-9.641-1.433zm11.586.43a41.098 41.098 0 00-1.92-6.897c1.876-.265 3.94-.196 6.199.196a9.923 9.923 0 01-4.279 6.701z"></path></svg>
            </a>
          </nav>
          <nav class="space-x-2 sm:space-x-4">
            <a href="javascript:void(0)" class="font-medium text-gray-900 hover:text-gray-500">
              About
            </a>
            <a href="javascript:void(0)" class="font-medium text-gray-900 hover:text-gray-500">
              Terms of Service
            </a>
            <a href="javascript:void(0)" class="font-medium text-gray-900 hover:text-gray-500">
              Privacy Policy
            </a>
          </nav>
          <div class="text-gray-600">
            <span class="font-medium">Company Inc</span> ©
          </div>
        </div>
      </footer>
      <!-- END Footer: Simple With Social -->
    </main>
    <!-- END Page Content -->
  </div>
  <!-- END Page Container -->

</x-guest-layout>
