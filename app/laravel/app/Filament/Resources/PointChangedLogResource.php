<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PointChangedLogResource\Pages;
use App\Filament\Resources\PointChangedLogResource\RelationManagers;
use App\Models\PointChangedLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class PointChangedLogResource extends Resource
{
    protected static ?string $model = PointChangedLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),

                TextColumn::make('user_id')
                    ->label('user_id')
                    ->searchable()
                    ->copyable(), // 클릭 시 복사 기능

                TextColumn::make('reference_id')
                    ->label('reference_id')
                    ->searchable()
                    ->copyable(), // 클릭 시 복사 기능

                TextColumn::make('before_amount')
                    ->label('before_amount')
                    ->numeric()
                    ->color(fn (string $state): string => $state[0] === '-' ? 'danger' : 'success'),
                    
                    TextColumn::make('changed_amount')
                    ->label('changed_amount')
                    ->numeric()
                    ->color(fn (string $state): string => $state[0] === '-' ? 'danger' : 'success'),

                TextColumn::make('after_amount')
                    ->label('after_amount')
                    ->numeric()
                    ->color(fn (string $state): string => $state[0] === '-' ? 'danger' : 'success'),
                
                TextColumn::make('type')
                    ->label('type')
                    ->searchable(),
                
                TextColumn::make('admin_id')
                    ->label('admin_id')
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label('updated_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),

                // 4. 생성일
                TextColumn::make('created_at')
                    ->label('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPointChangedLogs::route('/'),
            'create' => Pages\CreatePointChangedLog::route('/create'),
            'edit' => Pages\EditPointChangedLog::route('/{record}/edit'),
        ];
    }
}
