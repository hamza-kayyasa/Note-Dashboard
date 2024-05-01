<x-app-layout>
    <div class="note-container py-12">
        <a href="{{ route('note.create') }}" class="new-note-btn">
            New Note
        </a>



        <div class="notes">
            @foreach ($notes as $note)
                <div class="note">
                    <div class="note-body">
                        {{ Str::words($note->Note, 30) }}
                    </div>
                    <div class="note-buttons">
                        <a href="{{ route('note.show', $note) }}" class="note-edit-button">View</a>
                        <a href="{{ route('note.edit', $note) }}" class="note-edit-button">Edit</a>
                        <form action="{{ route('note.destroy', $note) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="note-delete-button">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>


            <div class="p-6">
                {{$notes->Links()}}
            </div>

    </div>

    <script>
        $(document).ready(function(){
         $('#search').on('keyup',function(){
             var query= $(this).val();
             $.ajax({
                url:"search",
                type:"GET",
                data:{'search':query},
                success:function(data){
                    $('#search_list').html(data);
                }
         });
         //end of ajax call
        });
        });
    </script>
</x-app-layout>