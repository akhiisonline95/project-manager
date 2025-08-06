<?php
$pageTitle = 'Dashboard';
$this->view("layouts/header");
$user = $user ?? [];
$filters = $filters ?? [];
$offset = array_key_exists('offset', $filters) ? (int)$filters['offset'] : 0;
?>

<div class="main-content">
    <?php if ($user['role'] === 'admin'): ?>
        <h2 class="mb-4">Admin Dashboard</h2>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Projects</h5>
                        <p class="display-4"><?= (int)($projectCount ?? 0) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Tasks</h5>
                        <p class="display-4"><?= (int)($taskCount ?? 0) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title mb-3">User Workloads</h5>
                        <ul class="list-group list-group-flush">
                            <?php if (!empty($userWorkloads)): ?>
                                <?php foreach ($userWorkloads as $workload): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= htmlspecialchars($workload['username']) ?>
                                        <span class="badge bg-primary rounded-pill"><?= (int)$workload['task_count'] ?></span>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="list-group-item">No workload data.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
    <h2 class="mb-4">My Assigned Tasks</h2>

    <?php if (!empty($assignedTasks)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Project</th>
                    <th>Title</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($assignedTasks as $key=>$task): ?>
                    <tr  data-task-id="<?=$task['id']?>" style="cursor:pointer;" class="view-task-btn">
                        <td><?=$offset+$key+1?></td>
                        <td><?= htmlspecialchars($task['project_title']) ?></td>
                        <td><?= htmlspecialchars($task['title']) ?></td>
                        <td><?= date("d-m-Y",strtotime($task['due_date'])) ?></td>
                        <td><?= ucfirst(htmlspecialchars($task['priority'])) ?></td>
                        <td><?= ucfirst(str_replace('_', ' ', htmlspecialchars($task['status']))) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php $this->view("layouts/pagination"); ?>
        </div>
    <?php else: ?>
        <p>You have no assigned tasks.</p>
    <?php endif; ?>

    <!-- Task Details Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="taskUpdateForm" method="post" novalidate action="index.php?controller=task&action=taskUpdate">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row">
                        <input type="hidden" name="id" id="task_id"/>

                        <div class="mb-3 col-md-6 col-12">
                            <label for="task_project_id" class="form-label">Project</label>
                            <input type="text" id="task_project_id" class="form-control" disabled/>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label for="task_title" class="form-label">Title</label>
                            <input type="text" id="task_title" class="form-control" disabled/>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label for="task_description" class="form-label">Description</label>
                            <textarea id="task_description" class="form-control" rows="4" disabled></textarea>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label for="task_due_date" class="form-label">Due Date</label>
                            <input type="text" id="task_due_date" class="form-control" disabled/>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label for="task_priority" class="form-label">Priority *</label>
                            <select name="priority" id="task_priority" class="form-select" required>
                                <option value="">Select Priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label for="task_status" class="form-label">Status *</label>
                            <select name="status" id="task_status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="todo">To Do</option>
                                <option value="in_progress">In Progress</option>
                                <option value="done">Done</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label for="task_assigned_to" class="form-label">Assigned To *</label>
                            <select name="assigned_to" id="task_assigned_to" class="form-select" required>
                                <option value="">Select User</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label for="task_created_by" class="form-label">Created By</label>
                            <input type="text" id="task_created_by" class="form-control" disabled/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const taskModal = new bootstrap.Modal(document.getElementById('taskModal'));

            $(document).on('click','.view-task-btn',function () {
                const taskId = $(this).data('task-id'); // Assume you add data-task-id attribute to the row
                if (!taskId) return;
                $.ajax({
                    url: 'index.php?controller=task&action=taskData&id=' + taskId,
                    method: 'GET',
                    dataType: 'json',
                    success: function (task) {
                        if (task.error) {
                            console.log(task.error);
                            return;
                        }

                        $('#task_id').val(task.id);
                        $('#task_project_id').val(task.project_title);
                        $('#task_title').val(task.title);
                        $('#task_description').val(task.description);
                        $('#task_due_date').val(moment(task.due_date).format('DD-MM-YYYY'));

                        $('#task_priority').val(task.priority);
                        $('#task_status').val(task.status);

                        let options = '<option value="">Select User</option>';
                        $.each(task.members, function (i, user) {
                            options += '<option value="' + user.id + '">' + user.username + '</option>';
                        });
                        $('#task_assigned_to').html(options);
                        $('#task_assigned_to').val(task.assigned_to);

                        $('#task_created_by').val(task.created_by_username || task.created_by);

                        taskModal.show();
                    },
                    error: function () {
                        alert('Failed to load task details.');
                    }
                });
            });
        });

    </script>

</div>
<?php endif; ?>

<?php $this->view("layouts/footer"); ?>
