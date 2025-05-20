<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nisn')
                    ->label('NISN')
                    ->required()
                    ->maxLength(10)
                    ->unique(),
                Forms\Components\TextInput::make('nis')
                    ->label('NIS')
                    ->required()
                    ->maxLength(8)
                    ->unique(),
                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(35),
                Forms\Components\Select::make('kelas')
                    ->label('Kelas')
                    ->required()
                    ->options([
                        'VIIA' => 'VII A',
                        'VIIB' => 'VII B',
                        'VIIC' => 'VII C',
                        'VIIIA' => 'VIII A',
                        'VIIIB' => 'VIII B',
                        'VIIIC' => 'VIII C',
                        'IXA' => 'IX A',
                        'IXB' => 'IX B',
                        'IXC' => 'IX C',
                    ]),
                Forms\Components\Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->required()
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->nullable()
                    ->maxLength(65535),
                Forms\Components\TextInput::make('phone')
                    ->label('Telepon')
                    ->tel()
                    ->nullable()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    }),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email') // Jika Anda ingin menampilkan email di tabel
                    ->label('Email Akun'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas')
                    ->options([
                        'VIIA' => 'VII A',
                        'VIIB' => 'VII B',
                        'VIIC' => 'VII C',
                        'VIIIA' => 'VIII A',
                        'VIIIB' => 'VIII B',
                        'VIIIC' => 'VIII C',
                        'IXA' => 'IX A',
                        'IXB' => 'IX B',
                        'IXC' => 'IX C',
                    ]),
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function afterCreate(Form $form, Student $record): void
    {
        try {
            // Buat email unik untuk siswa
            $email = Str::slug($record->nama) . '.' . $record->id . '@student.local';
            $defaultPassword = Str::random(10);

            $user = User::create([
                'student_id' => $record->id,
                'name' => $record->nama,
                'email' => $email,
                'password' => Hash::make($defaultPassword),
            ]);

            // Assign role 'student' jika ada
            $studentRole = Role::where('name', 'student')->first();
            if ($studentRole) {
                $user->assignRole($studentRole);
            } else {
                \Illuminate\Support\Facades\Log::warning('Role "student" tidak ditemukan.');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating user in afterCreate: ' . $e->getMessage());
            throw new \Exception('Gagal membuat akun pengguna secara otomatis.');
        }
    }

    public static function afterSave(Form $form, Model $record): void
    {
        $data = $form->getState();
        try {
            $user = User::where('student_id', $record->id)->first();
            if ($user) {
                $user->update(['name' => $data['nama']]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating user in afterSave: ' . $e->getMessage());
        }
    }
}
