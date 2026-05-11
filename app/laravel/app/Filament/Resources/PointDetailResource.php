<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PointDetailResource\Pages;
use App\Filament\Resources\PointDetailResource\RelationManagers;
use App\Models\PointDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class PointDetailResource extends Resource
{
    protected static ?string $model = PointDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //유효기간 수정
                Forms\Components\DateTimePicker::make('expire_at')
                    ->label('expire_at')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('id')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('user_id')
                    ->label('user_id')
                    ->searchable()
                    ->copyable(), // 클릭 시 복사 기능
                
                TextColumn::make('origin_amount')
                    ->label('origin_amount')
                    ->numeric()
                    ->color(fn (string $state): string => $state[0] === '-' ? 'danger' : 'success'),
                
                TextColumn::make('used_amount')
                    ->label('used_amount')
                    ->numeric()
                    ->color(fn (string $state): string => $state[0] === '-' ? 'danger' : 'success'),

                TextColumn::make('remain_amount')
                    ->label('remain_amount')
                    ->numeric()
                    ->color(fn (string $state): string => $state[0] === '-' ? 'danger' : 'success'),

                TextColumn::make('used_flag')
                    ->label('used_flag')
                    ->searchable(),

                TextColumn::make('expire_at')
                    ->label('expire_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),

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
            ->defaultSort('id', 'desc')
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
            'index' => Pages\ListPointDetails::route('/'),
            'create' => Pages\CreatePointDetail::route('/create'),
            'edit' => Pages\EditPointDetail::route('/{record}/edit'),
        ];
    }
}
