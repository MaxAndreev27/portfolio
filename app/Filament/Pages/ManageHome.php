<?php

namespace App\Filament\Pages;

use App\Models\HomeSettings;
use Filament\Pages\Page;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Grid;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Gate;

class ManageHome extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.manage-home';

    protected static ?string $title = 'Manage Home Page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWindow;

    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public function mount(): void
    {
        $settings = HomeSettings::first();
        $this->form->fill($settings?->toArray() ?? []);
    }

    public static function canAccess(): bool
    {
        return Gate::allows('update', HomeSettings::first() ?? new HomeSettings());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->persistTab()
                    ->tabs([
                        Tab::make('Hero')
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                Grid::make(3)
                                    ->columnSpanFull()
                                    ->schema([
                                        Group::make()
                                            ->columns(2)
                                            ->columnSpan(2)
                                            ->schema([
                                                TextInput::make('hero_title')
                                                    ->columnSpanFull()
                                                    ->required()
                                                    ->maxLength(100),

                                                TextInput::make('hero_description')
                                                    ->columnSpanFull()
                                                    ->maxLength(255),

                                                TextInput::make('hero_button_about')
                                                    ->maxLength(50),

                                                TextInput::make('hero_button_contact')
                                                    ->maxLength(50),
                                            ]),
                                        Group::make()
                                            ->columnSpan(1)
                                            ->schema([
                                                FileUpload::make('hero_image')
                                                    ->image()
                                                    ->maxSize(1024)
                                                    ->disk('public')
                                                    ->visibility('public')
                                                    ->directory('home-page')
                                                    ->getUploadedFileNameForStorageUsing(
                                                        fn(TemporaryUploadedFile $file): string => (string) str('hero-avatar')
                                                            ->slug()
                                                            ->limit(20, '')
                                                            ->append('-' . now()->format('Y-m-d-H-i'))
                                                            ->append('.' . $file->getClientOriginalExtension())
                                                    )
                                                    ->moveFiles()
                                                    ->imageEditor()
                                                    ->imageEditorAspectRatioOptions([
                                                        '16:9' => '16:9',
                                                        '4:3' => '4:3',
                                                        '1:1' => '1:1',
                                                    ])
                                                    ->imageEditorMode(2)
                                                    ->automaticallyCropImagesToAspectRatio('1:1')
                                                    ->automaticallyResizeImagesMode('cover'),

                                                Toggle::make('hero_is_featured')
                                                    ->onColor('success')
                                                    ->offColor('danger'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('About')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Grid::make(4)
                                    ->columnSpanFull()
                                    ->schema([
                                        Group::make()
                                            ->columns(3)
                                            ->columnSpan(3)
                                            ->schema([
                                                TextInput::make('about_title')
                                                    ->columnSpanFull()
                                                    ->maxLength(100),

                                                Repeater::make('about_timeline')
                                                    ->label('Work experience (Timeline)')
                                                    ->columnSpanFull()
                                                    ->schema([
                                                        TextInput::make('period')
                                                            ->label('Period')
                                                            ->placeholder('e.g. 2015-2023')
                                                            ->required(),
                                                        RichEditor::make('description')
                                                            ->label('Description')
                                                            ->toolbarButtons([
                                                                'link',
                                                                'bold',
                                                            ])
                                                            ->required(),
                                                    ])
                                                    ->itemLabel(fn(array $state): ?string => $state['period'] ?? null)
                                                    ->collapsible()
                                                    ->cloneable()
                                                    ->reorderableWithButtons(),

                                                Repeater::make('about_skills')
                                                    ->label(' List of skills and competencies')
                                                    ->columnSpanFull()
                                                    ->schema([
                                                        TextInput::make('category')
                                                            ->label('Category')
                                                            ->placeholder('e.g. Programming Languages')
                                                            ->required(),
                                                        TextInput::make('content')
                                                            ->label('List of skills')
                                                            ->placeholder('e.g. PHP, JavaScript, TypeScript')
                                                            ->required(),
                                                    ])
                                                    ->itemLabel(fn(array $state): ?string => $state['category'] ?? null) // Показує назву категорії у заголовку блоку
                                                    ->collapsible()
                                                    ->cloneable()
                                                    ->reorderableWithButtons()
                                            ]),
                                        Group::make()
                                            ->columnSpan(1)
                                            ->schema([
                                                Toggle::make('about_is_featured')
                                                    ->inline(false)
                                                    ->onColor('success')
                                                    ->offColor('danger'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Projects')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Grid::make(4)
                                    ->columnSpanFull()
                                    ->schema([
                                        Group::make()
                                            ->columns(3)
                                            ->columnSpan(3)
                                            ->schema([
                                                TextInput::make('projects_title')
                                                    ->columnSpanFull()
                                                    ->maxLength(100),
                                            ]),
                                        Group::make()
                                            ->columnSpan(1)
                                            ->schema([
                                                Toggle::make('projects_is_featured')
                                                    ->inline(false)
                                                    ->onColor('success')
                                                    ->offColor('danger'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Technology')
                            ->icon('heroicon-o-rocket-launch')
                            ->schema([
                                Grid::make(4)
                                    ->columnSpanFull()
                                    ->schema([
                                        Group::make()
                                            ->columns(3)
                                            ->columnSpan(3)
                                            ->schema([]),
                                        Group::make()
                                            ->columnSpan(1)
                                            ->schema([
                                                Toggle::make('technology_is_featured')
                                                    ->inline(false)
                                                    ->onColor('success')
                                                    ->offColor('danger'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Contact')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                Grid::make(4)
                                    ->columnSpanFull()
                                    ->schema([
                                        Group::make()
                                            ->columns(3)
                                            ->columnSpan(3)
                                            ->schema([
                                                TextInput::make('contact_title')
                                                    ->label('Contact form title')
                                                    ->columnSpanFull()
                                                    ->maxLength(100),
                                            ]),
                                        Group::make()
                                            ->columnSpan(1)
                                            ->schema([
                                                Toggle::make('contact_is_featured')
                                                    ->inline(false)
                                                    ->onColor('success')
                                                    ->offColor('danger'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Footer')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Grid::make(4)
                                    ->columnSpanFull()
                                    ->schema([
                                        Group::make()
                                            ->columns(3)
                                            ->columnSpan(3)
                                            ->schema([
                                                TextInput::make('footer_copyright')
                                                    ->columnSpanFull()
                                                    ->label('Footer copyright')
                                                    ->placeholder('Copyright text in footer')
                                                    ->maxLength(255),

                                                TextInput::make('footer_powered')
                                                    ->columnSpanFull()
                                                    ->label('Footer powered')
                                                    ->placeholder('Text under copyright')
                                                    ->maxLength(255),

                                                Repeater::make('footer_social_links')
                                                    ->columnSpanFull()
                                                    ->grid(2)
                                                    ->label('Social links')
                                                    ->schema([
                                                        TextInput::make('label')
                                                            ->label('Label (aria-label)')
                                                            ->placeholder('GitHub')
                                                            ->required()
                                                            ->maxLength(50),

                                                        TextInput::make('url')
                                                            ->label('Link (href)')
                                                            ->url()
                                                            ->required()
                                                            ->maxLength(255),

                                                        FileUpload::make('icon')
                                                            ->label('Іконка (SVG)')
                                                            ->image()
                                                            ->maxSize(1024)
                                                            ->required()
                                                            ->disk('public')
                                                            ->visibility('public')
                                                            ->directory('social-icons')
                                                            ->getUploadedFileNameForStorageUsing(
                                                                fn(TemporaryUploadedFile $file): string => (string) str('icon')
                                                                    ->slug()
                                                                    ->limit(20, '')
                                                                    ->append('-' . now()->format('Y-m-d-H-i'))
                                                                    ->append('.' . $file->getClientOriginalExtension())
                                                            )
                                                            ->moveFiles()
                                                            ->imageEditor()
                                                            ->imageEditorAspectRatioOptions([
                                                                '1:1' => '1:1',
                                                            ])
                                                            ->imageEditorMode(2)
                                                            ->automaticallyCropImagesToAspectRatio('1:1')
                                                            ->automaticallyResizeImagesMode('cover'),
                                                    ])
                                                    ->itemLabel(fn(array $state): ?string => $state['label'] ?? null)
                                                    ->collapsible()
                                                    ->cloneable()
                                                    ->reorderableWithButtons(),
                                            ]),
                                        Group::make()
                                            ->columnSpan(1)
                                            ->schema([]),
                                    ]),
                            ]),

                        Tab::make('SEO')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->helperText('SEO Title')
                                    ->maxLength(60)
                                    ->reactive()
                                    ->helperText(function ($state) {
                                        return (60 - mb_strlen($state ?? '')) . " left";
                                    }),

                                Textarea::make('seo_description')
                                    ->label('SEO Description')
                                    ->helperText('SEO Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->reactive()
                                    ->helperText(function ($state) {
                                        return (160 - mb_strlen($state ?? '')) . " left";
                                    }),
                            ])
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->submit('save')
                ->label('Save changes')
                ->color('primary')
                ->extraAttributes([
                    'style' => 'margin-top: 3rem;',
                ]),
        ];
    }

    public function getCachedFormActions(): array
    {
        return $this->getFormActions();
    }

    public function save(): void
    {
        $state = $this->form->getState();
        HomeSettings::updateOrCreate(['id' => 1], $state);

        Notification::make()
            ->title('Saved successfully!')
            ->success()
            ->send();
    }
}
