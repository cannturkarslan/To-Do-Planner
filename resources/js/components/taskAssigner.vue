<template>
  <div>
    <h2 class="main-title">Task Assignments (Total Weeks: {{ totalWeeks }})</h2>
    <div v-for="week in weeklyAssignments" :key="week.weekNumber" class="week-container">
      <h3 class="week-title">Week {{ week.weekNumber }}</h3>
      <div class="developers-grid">
        <div v-for="developer in week.developers" :key="developer.name" class="developer-card">
          <h4 class="developer-name">{{ developer.name }}</h4>
          <ul class="task-list">
            <li v-for="task in developer.tasks" :key="task.id" class="task-item">
              <span class="task-title">{{ task.title }}</span>
              <span class="task-hours">{{ task.hours.toFixed(2) }} hours</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    assignments: {
      type: Array,
      required: true
    },
    totalWeeks: {
      type: Number,
      required: true
    }
  },
  computed: {
    weeklyAssignments() {
      const weeks = {};
      this.assignments.forEach(assignment => {
        if (!weeks[assignment.week]) {
          weeks[assignment.week] = { weekNumber: assignment.week, developers: {} };
        }
        if (!weeks[assignment.week].developers[assignment.developer]) {
          weeks[assignment.week].developers[assignment.developer] = { name: assignment.developer, tasks: [] };
        }
        weeks[assignment.week].developers[assignment.developer].tasks.push({
          title: assignment.task,
          hours: assignment.hours
        });
      });
      return Object.values(weeks);
    }
  }
};
</script>