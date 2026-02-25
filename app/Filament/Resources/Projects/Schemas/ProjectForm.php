<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Str;
use Filament\Forms\Components\DateTimePicker;
use Filament\Support\Icons\Heroicon;
use App\Enums\ProjectStatus;
use Filament\Forms\Components\TagsInput;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Main information')
                            ->columns(2)
                            ->columnSpan(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->minLength(5)
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        $set('title', Str::ucfirst($state));
                                        if (! $get('slug')) {
                                            $set('slug', Str::slug($state));
                                        }
                                    })
                                    ->dehydrateStateUsing(fn($state) => Str::ucfirst($state)),

                                TextInput::make('slug')
                                    ->belowContent('The Slug is generated automatically after filling in the Title, but you can also edit it manually.')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                                    ->notIn([
                                        'admin',
                                        'login',
                                        'logout',
                                        'password',
                                        'register',
                                        'dashboard',
                                        'api',
                                        'settings',
                                        'profile',
                                        'appearance',
                                        'two-factor',
                                        'telescope',
                                        'telescope-api',
                                        'notifications',
                                        'create',
                                        'edit',
                                    ])
                                    ->validationMessages([
                                        'unique' => 'Такий slug вже зайнятий, придумайте інший.',
                                        'regex' => 'The slug should contain only lowercase Latin letters, numbers, and hyphens (e.g. "my-project").',
                                        'not_in' => 'Цей slug зарезервований системою.',
                                    ]),

                                RichEditor::make('description')
                                    ->columnSpanFull()
                                    ->maxLength(5000)
                                    ->reactive()
                                    ->helperText(function ($state) {
                                        return (5000 - mb_strlen(html_entity_decode(strip_tags($state ?? '')))) . " left";
                                    })
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('projects/content')
                                    ->fileAttachmentsVisibility('public'),
                                Textarea::make('excerpt')
                                    ->columnSpanFull()
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->reactive()
                                    ->helperText(function ($state) {
                                        return (160 - mb_strlen($state ?? '')) . " left";
                                    })
                                    ->hintAction(
                                        Action::make('generateExcerpt')
                                            ->label('Generate from Description')
                                            ->icon('heroicon-m-sparkles')
                                            ->action(function (Set $set, Get $get) {
                                                $description = $get('description');
                                                $cleanText = strip_tags($description ?? '');
                                                $set('excerpt', Str::limit($cleanText, 160, ''));
                                            })
                                    ),
                            ]),

                        Section::make('Addition')
                            ->columnSpan(1)
                            ->schema([
                                FileUpload::make('image')
                                    ->image()
                                    ->maxSize(1024)
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('projects')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn(TemporaryUploadedFile $file, Get $get): string => str($get('title'))
                                            ->slug()
                                            ->limit(20, '')
                                            ->append('-' . now()->format('Y-m-d-H-i'))
                                            ->append('.' . $file->getClientOriginalExtension())
                                    )
                                    ->moveFiles()
                                    ->imageEditor()
                                    ->imageEditorAspectRatioOptions([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                        null,
                                    ])
                                    ->imageAspectRatio('16:9')
                                    ->automaticallyOpenImageEditorForAspectRatio()
                                    ->automaticallyResizeImagesMode('cover'),

                                TagsInput::make('tags')
                                    ->placeholder('Add tag...')
                                    ->color('primary')
                                    ->separator(',')
                                    ->reorderable()
                                    ->trim()
                                    ->nestedRecursiveRules([
                                        'max:20',
                                    ])
                                    ->suggestions([
                                        'Vue',
                                        'Python',
                                        'Laravel',
                                        'Tailwind',
                                        'PHP',
                                        'Inertia',
                                    ]),

                                Select::make('status')
                                    ->required()
                                    ->selectablePlaceholder(false)
                                    ->options(ProjectStatus::class)
                                    ->default(ProjectStatus::Published)
                                    ->native(false)
                                    ->live()
                                    ->suffixIcon(fn($state) => $state instanceof ProjectStatus ? $state->getIcon() : 'heroicon-m-chevron-down')
                                    ->suffixIconColor(fn($state) => $state instanceof ProjectStatus ? $state->getColor() : 'gray'),

                                TextInput::make('url')
                                    ->label('Website link')
                                    ->maxLength(255)
                                    ->suffixIcon(Heroicon::Link)
                                    ->url(),
                                TextInput::make('github_url')
                                    ->label('Github link')
                                    ->maxLength(255)
                                    ->suffixIcon(Heroicon::ServerStack)
                                    ->url(),

                                DatePicker::make('completed_at')
                                    ->suffixIcon(Heroicon::CalendarDays)
                                    ->maxDate(now()),

                                Toggle::make('is_featured')
                                    ->onColor('success')
                                    ->offColor('danger'),

                                TextInput::make('order')
                                    ->label('For manual sorting, high:0, low:100')
                                    ->suffixIcon(Heroicon::ChartBar)
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(1)
                                    ->default(1),

                                DateTimePicker::make('created_at')
                                    ->suffixIcon(Heroicon::CalendarDays)
                                    ->readOnly(),
                                DateTimePicker::make('updated_at')
                                    ->suffixIcon(Heroicon::CalendarDays)
                                    ->readOnly(),

                            ]),
                    ]),
            ]);
    }
}
