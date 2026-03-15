<x-guest-layout>

    <div style="display:flex; min-height:100vh;">

        <!-- LEFT SIDE -->
        <div style="width:50%; position:relative;">

            <div id="slider" style="position:absolute; inset:0; overflow:hidden;">

                <div class="slide" style="position:absolute; inset:0;
                        background-image:url('{{ asset('images/login/slide1.jpg') }}');
                        background-size:cover;
                        background-position:center;
                        opacity:1;
                        transition:opacity 0.7s;">
                </div>

                <div class="slide" style="position:absolute; inset:0;
                        background-image:url('{{ asset('images/login/slide2.jpg') }}');
                        background-size:cover;
                        background-position:center;
                        opacity:0;
                        transition:opacity 0.7s;">
                </div>

                <div class="slide" style="position:absolute; inset:0;
                        background-image:url('{{ asset('images/login/slide3.jpg') }}');
                        background-size:cover;
                        background-position:center;
                        opacity:0;
                        transition:opacity 0.7s;">
                </div>

            </div>

        </div>

        <!-- RIGHT SIDE - GLASS + PURPLE GLOW + ANIMATION -->
        <div class="w-1/2 flex items-center justify-center 
            bg-gradient-to-br from-purple-200/40 via-purple-100/30 to-white relative overflow-hidden">

            <!-- Purple Glow Background -->
            <div class="absolute w-[500px] h-[500px] 
                bg-purple-500/30 
                rounded-full blur-[120px] 
                -z-0 animate-pulse-slow">
            </div>

            <div class="w-full max-w-md px-8 relative z-10">

                <div class="rounded-3xl p-[1px] 
                    bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 
                    shadow-2xl shadow-purple-400/30 
                    animate-fade-slide">

                    <div class="backdrop-blur-2xl bg-white/70 
                        rounded-3xl p-10">

                        <h2 class="text-3xl font-extrabold 
                           bg-gradient-to-r from-purple-700 to-purple-500 
                           bg-clip-text text-transparent">
                            THE MICA MEDIA
                        </h2>

                        <p class="mt-2 text-sm text-purple-900/70">
                            Welcome back — sign in to continue
                        </p>

                        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                            @csrf

                            <!-- Email -->
                            <div>
                                <label class="text-sm font-semibold text-purple-800">
                                    Username
                                </label>

                                <input type="text" name="username" required class="mt-2 w-full rounded-2xl 
bg-white/80
border border-purple-200
px-4 py-3
text-purple-900
focus:outline-none
focus:ring-2 
focus:ring-purple-500
focus:border-purple-500
transition duration-300">
                            </div>

                            @if ($errors->any())
                            <div class="mb-4 text-red-600 text-sm">
                                {{ $errors->first() }}
                            </div>
                            @endif

                            <!-- Password -->
                            <div>
                                <label class="text-sm font-semibold text-purple-800">
                                    Password
                                </label>
                                <input type="password" name="password" required class="mt-2 w-full rounded-2xl 
                                      bg-white/80
                                      border border-purple-200
                                      px-4 py-3
                                      text-purple-900
                                      focus:outline-none
                                      focus:ring-2 
                                      focus:ring-purple-500
                                      focus:border-purple-500
                                      transition duration-300">
                            </div>

                            <!-- Remember -->
                            <div class="flex items-center justify-between text-sm">
                                <label class="flex items-center gap-2 text-purple-800">
                                    <input type="checkbox" name="remember" class="rounded border-purple-300 
                                          text-purple-600 
                                          focus:ring-purple-500">
                                    Remember me
                                </label>

                                <a href="#" onclick="alert('Please contact the administrator to reset your password.')" class="text-purple-700 hover:text-purple-900 font-semibold">
                                    Forgot password?
                                </a>
                            </div>

                            <!-- Button -->
                            <button type="submit" class="w-full rounded-2xl py-3 
                                   bg-gradient-to-r 
                                   from-purple-600 
                                   via-purple-700 
                                   to-purple-800
                                   hover:scale-[1.02]
                                   active:scale-95
                                   text-white font-bold
                                   shadow-xl shadow-purple-500/40
                                   transition duration-300">
                                Sign In
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        const slides = document.querySelectorAll('.slide');
        let current = 0;

        setInterval(() => {
            slides[current].style.opacity = 0;
            current = (current + 1) % slides.length;
            slides[current].style.opacity = 1;
        }, 3000);

    </script>

</x-guest-layout>
