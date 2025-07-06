@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Quản lý Bình luận Bài viết</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>ID</th>
                                <th>Bài viết</th>
                                <th>Người bình luận</th>
                                <th>Nội dung</th>
                                <th>Trạng thái</th>
                                <th>Ngày bình luận</th>
                                <th width="180px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($comments as $comment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $comment->id }}</td>
                                    <td>{{ $comment->news?->title ?? 'Không rõ' }}</td>
                                    <td>{{ $comment->user?->name ?? 'Ẩn danh' }}</td>
                                    <td class="{{ $comment->is_hidden ? 'text-muted fst-italic' : '' }}">
                                        {{ $comment->is_hidden ? '[Đã ẩn] ' : '' }}{{ $comment->content }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $comment->is_hidden ? 'secondary' : 'success' }}">
                                            {{ $comment->is_hidden ? 'Đã ẩn' : 'Hiển thị' }}
                                        </span>
                                    </td>
                                    <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        {{-- Nút Ẩn / Hiện --}}
                                        <form action="{{ route('admin.news-comments.toggle', $comment->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-{{ $comment->is_hidden ? 'secondary' : 'warning' }}"
                                                    title="{{ $comment->is_hidden ? 'Hiện bình luận' : 'Ẩn bình luận' }}">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </form>

                                        {{-- Nút Xoá --}}
                                        <form action="{{ route('admin.news-comments.destroy', $comment->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc muốn xoá bình luận này không?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xoá">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Không có bình luận nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $comments->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
