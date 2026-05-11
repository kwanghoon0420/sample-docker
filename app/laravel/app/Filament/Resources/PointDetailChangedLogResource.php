<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PointDetailChangedLogResource\Pages;
use App\Models\PointDetailChangedLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class PointDetailChangedLogResource extends Resource
{
    protected static ?string $model = PointDetailChangedLog::class;

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
                
                TextColumn::make('point_changed_log_id')
                    ->label('point_changed_log_id')
                    ->searchable()
                    ->copyable(), // 클릭 시 복사 기능
                
                TextColumn::make('point_detail_id')
                    ->label('point_detail_id')
                    ->searchable()
                    ->copyable(), // 클릭 시 복사 기능
                    
                TextColumn::make('user_id')
                    ->label('user_id')
                    ->searchable()
                    ->copyable(), // 클릭 시 복사 기능

                TextColumn::make('changed_amount')
                    ->label('changed_amount')
                    ->numeric()
                    ->color(fn (string $state): string => $state[0] === '-' ? 'danger' : 'success'),

                TextColumn::make('type')
                    ->label('type')
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
            'index' => Pages\ListPointDetailChangedLogs::route('/'),
            'create' => Pages\CreatePointDetailChangedLog::route('/create'),
            'edit' => Pages\EditPointDetailChangedLog::route('/{record}/edit'),
        ];
    }
}
