<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Notification;
use App\CommentNotification;
use App\LogLine;

class CreateInboxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->morphs('loggable');
            $table->integer('egora_id')->default(config('egoras.default.id'));
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');
        });
        
        $rows = Notification::with('idea')->withTrashed()->get();
        foreach ($rows as $row)
        {
            $line = new LogLine();
            $line->user_id = $row->receiver_id;
            $line->egora_id = $row->idea->egora_id;
            $line->deleted_at = $row->deleted_at;
            $line->created_at = $row->created_at;

            $row->logline()->save($line);
        }
        
        $rows = CommentNotification::withTrashed()->get();
        foreach ($rows as $row)
        {
            $line = new LogLine();
            $line->user_id = $row->receiver_id;
            $line->egora_id = $row->egora_id;
            $line->deleted_at = $row->deleted_at;
            $line->created_at = $row->created_at;

            $row->logline()->save($line);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_lines');
    }
}
