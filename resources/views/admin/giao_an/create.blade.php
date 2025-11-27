@extends('layouts.app')

@section('title', 'Tạo giáo án mới')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tạo giáo án mới</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.giao-an.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>
</div>

<form action="{{ route('admin.giao-an.store') }}" method="POST" id="giaoAnForm">
    @csrf
    
    <div class="row">
        <!-- General Info -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin chung</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tên giáo án <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ten_giao_an" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mục tiêu <span class="text-danger">*</span></label>
                        <select class="form-select" name="muc_tieu" required>
                            <option value="tang_co">Tăng cơ</option>
                            <option value="giam_mo">Giảm mỡ</option>
                            <option value="tang_suc_manh">Tăng sức mạnh</option>
                            <option value="duy_tri">Duy trì</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số ngày tập/tuần <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="so_ngay" min="1" max="7" value="3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="mo_ta" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exercises -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chi tiết bài tập</h5>
                    <button type="button" class="btn btn-success btn-sm" id="addExerciseBtn">
                        <i class="bi bi-plus-lg me-1"></i>Thêm bài tập
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" id="exercisesTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 100px;">Ngày</th>
                                    <th>Bài tập</th>
                                    <th style="width: 80px;">Hiệp</th>
                                    <th style="width: 100px;">Reps</th>
                                    <th>Ghi chú</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="exercisesContainer">
                                <!-- Dynamic rows here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 text-center text-muted" id="emptyState">
                        Chưa có bài tập nào. Nhấn nút "Thêm bài tập" để bắt đầu.
                    </div>
                </div>
            </div>
            
            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Lưu giáo án
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Template for new row -->
<template id="exerciseRowTemplate">
    <tr>
        <td>
            <input type="number" class="form-control form-control-sm" name="exercises[INDEX][ngay_tap]" value="1" min="1" required>
        </td>
        <td>
            <select class="form-select form-select-sm" name="exercises[INDEX][id_bai_tap]" required>
                <option value="">-- Chọn bài --</option>
                @foreach($baiTaps as $bt)
                    <option value="{{ $bt->id }}">{{ $bt->ten_bai_tap }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" name="exercises[INDEX][so_hiep]" value="3" min="1" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="exercises[INDEX][so_lan]" value="10-12" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="exercises[INDEX][ghi_chu]">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm remove-row">
                <i class="bi bi-x"></i>
            </button>
        </td>
    </tr>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let exerciseIndex = 0;
        const container = document.getElementById('exercisesContainer');
        const template = document.getElementById('exerciseRowTemplate');
        const emptyState = document.getElementById('emptyState');
        const addBtn = document.getElementById('addExerciseBtn');

        addBtn.addEventListener('click', function() {
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('tr');
            
            // Replace INDEX placeholder
            row.innerHTML = row.innerHTML.replace(/INDEX/g, exerciseIndex++);
            
            container.appendChild(row);
            emptyState.style.display = 'none';
            
            // Add remove handler
            row.querySelector('.remove-row').addEventListener('click', function() {
                row.remove();
                if (container.children.length === 0) {
                    emptyState.style.display = 'block';
                }
            });
        });
    });
</script>
@endsection
