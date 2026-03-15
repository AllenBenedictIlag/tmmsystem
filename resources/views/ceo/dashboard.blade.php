<x-layouts.app-purple title="CEO Dashboard">

    <div class="max-w-7xl mx-auto py-8 space-y-10">

        <!-- ===================================================== -->
        <!-- EXECUTIVE KPI SECTION -->
        <!-- ===================================================== -->

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                <div class="text-gray-500">Total Clients</div>
                <div class="text-4xl font-bold mt-2 text-purple-700">
                    {{ $totalClients }}
                </div>
            </div>

            <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                <div class="text-gray-500">Active Projects</div>
                <div class="text-4xl font-bold mt-2 text-purple-700">
                    {{ $activeProjects }}
                </div>
            </div>

            <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                <div class="text-gray-500">Pending Approvals</div>
                <div class="text-4xl font-bold mt-2 text-purple-700">
                    {{ $pendingApprovals }}
                </div>
            </div>

            <div class="border border-[#b9aef5] rounded-xl p-6 bg-white text-center">
                <div class="text-gray-500">Overdue Tasks</div>
                <div class="text-4xl font-bold mt-2 text-red-500">
                    {{ $overdueTasks }}
                </div>
            </div>

        </div>


        <!-- ===================================================== -->
        <!-- CONTRACT ANALYTICS -->
        <!-- ===================================================== -->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="border border-[#b9aef5] rounded-xl bg-white">

                <div class="bg-[#a598eb] text-white px-6 py-3 font-semibold">
                    Contract Overview
                </div>

                <div class="p-6 grid grid-cols-2 gap-6 text-center">

                    <div>
                        <div class="text-sm text-gray-500">Draft Contracts</div>
                        <div class="text-3xl font-semibold text-purple-700">
                            {{ $draftContracts }}
                        </div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Signed Contracts</div>
                        <div class="text-3xl font-semibold text-purple-700">
                            {{ $signedContracts }}
                        </div>
                    </div>

                </div>

            </div>


            <!-- CEO ACTION CENTER -->

            <div class="border border-[#b9aef5] rounded-xl bg-white">

                <div class="bg-[#a598eb] text-white px-6 py-3 font-semibold">
                    CEO Action Center
                </div>

                <div class="p-6 space-y-4">

                    <div class="flex justify-between">
                        <span>Pending Task Approvals</span>
                        <span class="font-semibold text-purple-700">
                            {{ $pendingApprovals }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Content Ready to Post</span>
                        <span class="font-semibold text-purple-700">
                            {{ $readyToPost }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Overdue Creative Tasks</span>
                        <span class="font-semibold text-red-500">
                            {{ $overdueTasks }}
                        </span>
                    </div>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- PROJECT PERFORMANCE -->
        <!-- ===================================================== -->

        <div class="border border-[#b9aef5] rounded-xl bg-white">

            <div class="bg-[#a598eb] text-white px-6 py-3 font-semibold">
                Project Performance
            </div>

            <div class="divide-y">

                @foreach($topProjects as $project)

                @php
                $progress = $project->total_tasks > 0
                ? round(($project->completed_tasks / $project->total_tasks) * 100)
                : 0;
                @endphp

                <div class="p-6 flex justify-between items-center">

                    <div>
                        <div class="font-semibold text-gray-800">
                            {{ $project->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $project->completed_tasks }} / {{ $project->total_tasks }} tasks completed
                        </div>
                    </div>

                    <div class="text-lg font-semibold text-purple-700">
                        {{ $progress }}%
                    </div>

                </div>

                @endforeach

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- TEAM PRODUCTIVITY -->
        <!-- ===================================================== -->

        <div class="border border-[#b9aef5] rounded-xl bg-white">

            <div class="bg-[#a598eb] text-white px-6 py-3 font-semibold">
                Top Performing Creatives
            </div>

            <div class="divide-y">

                @foreach($topCreatives as $creative)

                <div class="p-6 flex justify-between">

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


        <!-- ===================================================== -->
        <!-- RECENT CEO APPROVALS -->
        <!-- ===================================================== -->

        <div class="border border-[#b9aef5] rounded-xl bg-white">

            <div class="bg-[#a598eb] text-white px-6 py-3 font-semibold">
                Recent CEO Approvals
            </div>

            <div class="divide-y">

                @forelse($recentApprovals as $task)

                <div class="p-6 flex justify-between">

                    <div>
                        <div class="font-medium">
                            {{ $task->title ?? 'Untitled Task' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Approved {{ $task->approved_at?->diffForHumans() }}
                        </div>
                    </div>

                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                        Approved
                    </span>

                </div>

                @empty

                <div class="p-6 text-gray-400 text-center">
                    No recent approvals.
                </div>

                @endforelse

            </div>

        </div>


    </div>

</x-layouts.app-purple>
