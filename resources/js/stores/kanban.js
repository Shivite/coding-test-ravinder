import { defineStore } from 'pinia'

export const useKanbanStore = defineStore('kanban', {
  state: () => {
    return {
      hoveredName: 'nothing',
      selectedTask: null,
      phases: [],
      users: [],
      creatingTask: false,
      editingTask: false,
      // Now we are using createTaskProps for both add and update but am not changin its name as it have been used on many places and I just want to add newcode so change must be minimum. So leaving it as it was.
      creatingTaskProps: {
        name: '',
        phase_id: null,
        user_id: null,
      },
      updateColumnsTask: false,
      self: null,
    }
  },
  actions: {
    unhoverTask() {
      this.hoveredName = 'nothing'
    },
    selectTask(task) {
      this.selectedTask = task
    },
    unselectTask(task) {
      this.selectedTask = null
    },
    hasSelectedTask() {
      return this.selectedTask !== null
    },
  },
})