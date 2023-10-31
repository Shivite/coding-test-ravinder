<template>
        <Teleport to="body">
          <users-statistic style="display: flex; justify-content: space-between;">
            <div class=""> 
                <div class="bg-white rounded-lg shadow-md p-4 m-5">
                <h2 class="text-2xl font-semibold mb-4">User Tasks</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tasks</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasks This Week</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasks This Month</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(userStat, index) in userStatistics?.usersWithTaskCounts" :key="index">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-800">{{ userStat.name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-blue-500">{{ userStat.total_task_count }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-blue-500">{{ userStat.completed_task_count_in_week }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-blue-500">{{ userStat.completed_task_count_in_month }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div class=""> 
                <div class="bg-white rounded-lg shadow-md p-4 m-5">
                    <h2 class="text-2xl font-semibold mb-4">Card Completion Statistics</h2>
                    <div class="p-4 bg-blue-100 border-l-4 border-blue-500">
                        <slot>Current Month Completions: <span style="font-weight: bold; float: right;">{{ userStatistics?.completedThisMonth }}</span></slot>
                    </div>
                    <div class="p-4 bg-blue-100 border-l-4 border-blue-500 mt-2">
                        <slot>Current Week Completions : <span style="font-weight: bold; float: right;">{{ userStatistics?.completedThisWeek }}</span></slot>
                    </div>
                </div>
            </div>
          </users-statistic>
        </Teleport>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useKanbanStore } from '../../stores/kanban';
  import axios from 'axios';
  
  const kanban = useKanbanStore();
  const userStatistics = ref([]);
  
  onMounted(async () => {
      await getUserCards();
  });
  
  const getUserCards = async () => {
      try {
          const response = await axios.get(`/api/statistics`);
          userStatistics.value = response.data; // Assuming response is an array of user statistics objects
      } catch (error) {
          console.error("Error fetching user statistics:", error);
      }
  }
  </script>
  