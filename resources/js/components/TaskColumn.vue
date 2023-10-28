<template>
<div class="w-[300px] bg-sky-950 rounded-lg shadow-lg">
    <div class="p-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg text-zinc-100 font-black mb-3">{{ kanban.phases[props.phase_id].name }} 
                <span class="inline-flex items-center justify-center w-5 h-5 mb-3  text-xs font-semibold text-blue-800 bg-green-100 rounded-full">
                    {{ kanban.phases[props.phase_id].tasks_count }}
                </span>
            </h2>
            <PlusIcon 
                @click="createTask()" 
                class="mb-3 h-6 w-6 text-white hover:cursor-pointer" 
                aria-hidden="true" />
        </div>
        <task-card v-if="kanban.phases[props.phase_id].tasks.length > 0" v-for="task in kanban.phases[props.phase_id].tasks" :task="task" />
        <!-- A card to create a new task -->
        <div class="w-full flex items-center justify-between bg-white text-gray-900 hover:cursor-pointer shadow-md rounded-lg p-3 relative"
            @click="createTask()">
            <span>Create a new task</span>
            <PlusIcon class="h-6 w-6" aria-hidden="true" />
        </div>
        <div class="w-full flex items-center justify-between bg-white text-gray-900 hover:cursor-pointer shadow-md rounded-lg p-3 relative mt-3"
            @click="confirmCardComplete()">
            <span>Mark cards as complete</span>
            <CheckCircleIcon class="h-6 w-6" aria-hidden="true" />
        </div>

    </div>
</div>
</template>

<script setup>
// get the props
import { useKanbanStore } from '../stores/kanban'
import { PlusIcon, CheckCircleIcon } from '@heroicons/vue/20/solid'

const kanban = useKanbanStore()

const props = defineProps({
    phase_id: {
        type: Number,
        required: true
    },
})

const createTask = function () {
    kanban.creatingTask = true;
    kanban.creatingTaskProps.phase_id = props.phase_id;
}

const confirmCardComplete = function () {
    if (window.confirm("Are you sure you want to proceed?")) {
        axios.put(`api/Phases/${kanban.phases[props.phase_id].id}/mark-tasks-completed`)
        .then(response => {
            console.log("response", response)
            refreshTasks();
        })
        .catch(error => {
            console.log("error", error)
        });
    }
}

const refreshTasks = async () => {
    try {
        const response = await axios.get('/api/tasks');
        const originalTasks = response.data;
        kanban.phases = originalTasks.reduce((acc, cur) => {
            acc[cur.id] = cur;
            return acc;
        }, {});
    } catch (error) {
        console.error('There was an error fetching the tasks!', error);
    }
}

</script>