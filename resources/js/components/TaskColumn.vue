<template>
<div class="w-[300px] bg-sky-950 rounded-lg shadow-lg scollbar-container">
    <div v-if="responseMessage" :class="messageClass" class="fancy-message">
      {{ responseMessage }}
    </div>
    <div class="p-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg text-zinc-100 font-black mb-3">{{ kanban.phases[props.phase_id].name }} 
                <span class="inline-flex items-center justify-center w-5 h-5 mb-3  text-xs font-semibold text-blue-800 bg-green-100 rounded-full">
                    {{ kanban.phases[props.phase_id].tasks_count }}
                </span>
            </h2>
            <div  class="flex">
            <TrashIcon  
                @click="removePhase()" 
                class="mb-3 h-6 w-6 text-white hover:cursor-pointer" 
                aria-hidden="true" />
            <PlusIcon 
                @click="createTask()" 
                class="mb-3 h-6 w-6 text-white hover:cursor-pointer" 
                aria-hidden="true" />
            </div>
        </div>
        <task-card v-if="kanban.phases[props.phase_id].tasks.length > 0" v-for="task in kanban.phases[props.phase_id].tasks" :task="task" />
        <!-- A card to create a new task -->
        <div class="w-full flex items-center justify-between bg-white text-gray-900 hover:cursor-pointer shadow-md rounded-lg p-3 relative"
            @click="createTask()">
            <span>Create a new task </span>
            <PlusIcon class="h-6 w-6" aria-hidden="true" />
        </div>     
        <div 
             v-if="kanban.phases[props.phase_id].tasks.length > 0"
            :class="{ 'disabled': kanban.phases[props.phase_id].completed }"
            class="w-full flex items-center justify-between bg-white text-gray-900 hover:cursor-pointer shadow-md rounded-lg p-3 relative mt-3"
            @click="confirmCardComplete()">
            <span>Mark cards as complete</span>
            <CheckCircleIcon class="h-6 w-6" aria-hidden="true" />
        </div>
    </div>
</div>
</template>

<script setup>
import { ref, computed } from 'vue';
const responseMessage = ref(null);
const messageType = ref(null);
// get the props
import { useKanbanStore } from '../stores/kanban'
import { PlusIcon, TrashIcon, CheckCircleIcon } from '@heroicons/vue/20/solid'

const kanban = useKanbanStore()

const props = defineProps({
    phase_id: {
        type: Number,
        required: true
    },

})

const messageClass = computed(() => {
  return {
    'alert': true,
    'alert-success': messageType.value === 'success',
    'alert-error': messageType.value === 'error',
  };
});



const createTask = function () {
    if(kanban.phases[props.phase_id].completed){
       if(!window.confirm("This phase is already completed. So created tasks must be a completed task. Still you want to continue?")){
        return;
       }
    }
    kanban.creatingTask = true;
    kanban.taskProps.phase_id = props.phase_id;
}

const removePhase = async () => {
    if(kanban.phases[props.phase_id].id){
        if (window.confirm("Are you sure you want to proceed?")) {
            try {
                let response = await axios.delete(`/api/phases/${kanban.phases[props.phase_id].id}`);
                responseMessage.value = response?.data?.message;
                messageType.value = 'success';
                refreshTasks();
            } catch (error) {
                responseMessage.value = error.response.data.error
                messageType.value = 'error';
            }
        }
    }else{
        responseMessage.value = "Phase value not provided!"
        messageType.value = 'error';
    }
}

const confirmCardComplete = function () {
    if (window.confirm("Are you sure you want to proceed?")) {
        axios.put(`api/Phases/${kanban.phases[props.phase_id].id}/mark-tasks-completed`)
        .then(response => {
            responseMessage.value = response?.data?.message;
            messageType.value = 'success';
            refreshTasks();
        })
        .catch(error => {
            responseMessage.value = error.response.data.error
            messageType.value = 'error';
        });
    }
}

const refreshTasks = async () => {
    try {
        const response = await axios.get('/api/tasks');
        const originalTasks = response.data;
        kanban.phases = originalTasks.reduce((acc, cur) => {
            acc[cur.id] = cur;
            console.log("acc", acc)
            return acc;
        }, {});
    } catch (error) {
        console.error('There was an error fetching the tasks!', error);
    }
}

</script>
<style scoped>
    .disabled {
        pointer-events: none; /* Disable interactions with the element */
        opacity: 0.5; /* Make it look disabled (you can adjust this as needed) */
    }
    .fancy-message {
    color: white;
    padding: 2px;
    text-align: center;
    border-radius: 5px;
    margin: 10px 5PX;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }
  .alert-success{
    background-color: #4CAF50;
  }
  .alert-error{
    background-color: #f07567;
  }
  .scollbar-container{
    overflow-y: auto; 
    max-height: 80vh;
  }
</style>
