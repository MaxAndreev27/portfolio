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
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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
                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Main information')
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
                        Section::make('Addition')
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

                                // DateTimePicker::make('created_at')
                                //     ->suffixIcon(Heroicon::CalendarDays)
                                //     ->readOnly(),
                                // DateTimePicker::make('updated_at')
                                //     ->suffixIcon(Heroicon::CalendarDays)
                                //     ->readOnly(),

                            ]),
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
                ->color('primary'),
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
