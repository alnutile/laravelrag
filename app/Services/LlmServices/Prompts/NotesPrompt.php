<?php

namespace App\Services\LlmServices\Prompts;

class NotesPrompt
{
    public static function prompt(string $input)
    {

        $now = now()->toISOString();

        $prompt = <<<PROMPT

### Context, Action, Result, Example (C.A.R.E.)
TODAY'S DATE IS: $now
**Context**: You are given a list of notes from a user. Each note contains information that may include actionable items.
**Action**:
1. Scan through each note to identify phrases or sentences that imply actionable items.
2. Extract these items and format them into a structured list of tasks, where each task is represented as an object with a date and a description.
**Result**: The output is a structured task list in JSON format (not encapsulated in Markdown code blocks) where each actionable item from the notes is formatted as `{date: "YYYY-MM-DD", description: "task description"}`.
**Example**:
- **Input Notes**:
  - "Meet with the marketing team on 2024-05-10 to discuss the new campaign strategies."
  - "Remember to call the supplier tomorrow about the order delay."
  - "Review the project report submitted last week."
- **Output Task List AS JSON SO I CAN RENDER WITH PHP**:
[
  { "date": "2024-05-10", "description": "Meet with the marketing team to discuss the new campaign strategies" },
  { "date": "2024-05-11", "description": "Call the supplier about the order delay" },
  { "date": "2024-05-12", "description": "Review the project report submitted last week" }
]

**Example**
- **Input Notes**:
  - "measure kitchen for custom cabinets - 5/20"
  - "call Mr. Smith to confirm paint color choices"
- **Output Task List AS JSON SO I CAN RENDER WITH PHP**:
[
  { "date": "2024-05-20", "description": "Measure kitchen for custom cabinets" },
  { "date": "", "description": "Call Mr. Smith to confirm paint color choices" }
]


## User Notes are below
$input


PROMPT;

        return $prompt;

    }
}
