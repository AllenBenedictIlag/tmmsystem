<x-layouts.app-purple title="Performance Dashboard">

    <div class="max-w-7xl mx-auto py-8 space-y-10">


        <!-- ================================= -->
        <!-- COMPANY PRODUCTIVITY -->
        <!-- ================================= -->

        <div>

            <h2 class="text-xl font-semibold text-gray-700 mb-4">
                Company Productivity
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

                <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                    <div class="text-gray-500">Total Projects</div>
                    <div class="text-3xl font-bold text-purple-700 mt-2">
                        {{ $projects }}
                    </div>
                </div>

                <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                    <div class="text-gray-500">Total Tasks</div>
                    <div class="text-3xl font-bold text-purple-700 mt-2">
                        {{ $totalTasks }}
                    </div>
                </div>

                <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                    <div class="text-gray-500">Completed Tasks</div>
                    <div class="text-3xl font-bold text-green-600 mt-2">
                        {{ $completedTasks }}
                    </div>
                </div>

                <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                    <div class="text-gray-500">Pending Tasks</div>
                    <div class="text-3xl font-bold text-red-500 mt-2">
                        {{ $pendingTasks }}
                    </div>
                </div>

            </div>

        </div>



        <!-- ================================= -->
        <!-- PERFORMANCE RATE -->
        <!-- ================================= -->

        <div>

            <h2 class="text-xl font-semibold text-gray-700 mb-4">
                Performance Rate
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="border border-[#b9aef5] rounded-xl p-8 bg-white text-center">

                    <div class="text-gray-500 mb-2">
                        Task Completion Rate
                    </div>

                    <div class="text-5xl font-bold text-purple-700">
                        {{ $taskCompletionRate }}%
                    </div>

                </div>

                <div class="border border-[#b9aef5] rounded-xl p-8 bg-white text-center">

                    <div class="text-gray-500 mb-2">
                        Total Creatives
                    </div>

                    <div class="text-5xl font-bold text-purple-700">
                        {{ $creatives->count() }}
                    </div>

                </div>

            </div>

        </div>



        <!-- ================================= -->
        <!-- ATTENDANCE ANALYTICS -->
        <!-- ================================= -->

        <div>

            <h2 class="text-xl font-semibold text-gray-700 mb-4">
                Attendance Analytics
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

                <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                    <div class="text-gray-500">Attendance Today</div>
                    <div class="text-3xl font-bold text-purple-700 mt-2">
                        {{ $attendanceToday }}
                    </div>
                </div>

                <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                    <div class="text-gray-500">Late Today</div>
                    <div class="text-3xl font-bold text-red-500 mt-2">
                        {{ $lateToday }}
                    </div>
                </div>

                <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                    <div class="text-gray-500">Abnormal Attendance</div>
                    <div class="text-3xl font-bold text-yellow-500 mt-2">
                        {{ $abnormalToday }}
                    </div>
                </div>

                <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                    <div class="text-gray-500">Overtime Minutes</div>
                    <div class="text-3xl font-bold text-purple-700 mt-2">
                        {{ $overtimeToday }}
                    </div>
                </div>

            </div>

        </div>



        <!-- ================================= -->
        <!-- TOP CREATIVES -->
        <!-- ================================= -->

        <div class="border border-[#b9aef5] rounded-xl bg-white">

            <div class="bg-[#a598eb] text-white px-6 py-3 font-semibold">
                Top Performing Creatives
            </div>

            <div class="divide-y">

                @foreach($topCreatives as $creative)

                <div class="flex justify-between items-center px-6 py-4">

                    <div class="font-medium text-gray-800">
                        {{ $creative->name }}
                    </div>

                    <div class="text-purple-700 font-semibold">
                        {{ $creative->completed_tasks }} tasks
                    </div>

                </div>

                @endforeach

            </div>

        </div>


    </div>

</x-layouts.app-purple>
