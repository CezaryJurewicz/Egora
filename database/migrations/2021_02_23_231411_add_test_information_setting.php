<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTestInformationSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('settings')->insert([
            'name' => 'test information',
            'type' => 'text',
            'value' => '[Markdown](http://en.wikipedia.com/wiki/Markdown)

# Heading
## Sub-heading
##### Sub-sub-heading

Heading
=======

Sub-heading
-----------

Paragraphs are separated
by a blank line.

Two spaces at the end of a line  
produces a line break.


Text attributes _italic_, 
**bold**, `monospace`.

Horizontal rule:

---

Strikethrough:
~~strikethrough~~

Bullet list:

  * apples
  * oranges
  * pears

Numbered list:

  1. lather
  2. rinse
  3. repeat

> Markdown uses email-style 
> characters for blockquoting.

Inline <abbr title="Hypertext Markup Language">HTML</abbr> is supported.'
        ]);        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
