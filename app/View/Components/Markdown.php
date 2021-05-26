<?php

namespace App\View\Components;

use Illuminate\Mail\Markdown as MarkdownParser;
use Illuminate\View\Component;

class Markdown extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function parseMarkdown($markdown)
    {
        return MarkdownParser::parse($markdown);
        // $markdown = new MarkdownParser(view(), config('mail.markdown'));

        // return $markdown->render($markdowndata);

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.markdown');
    }
}
